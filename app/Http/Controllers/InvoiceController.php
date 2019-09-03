<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Item;
use App\Models\Product;
use App\Models\Supplier;

use Auth;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'invoice']);
        $user = Auth::user();
        $suppliers = Supplier::all();

        $mod = new Invoice();
        $company_id = $reference_no = $supplier_id = $store_id = $period = $expiry_period = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('supplier_id') != ""){
            $supplier_id = $request->get('supplier_id');
            $mod = $mod->where('supplier_id', $supplier_id);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $supplier_array = Supplier::where('company', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $supplier_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('supplier_id', $supplier_array)
                        ->orWhere('issue_date', 'LIKE', "%$keyword%")
                        ->orWhere('due_date', 'LIKE', "%$keyword%")
                        ->orWhere('delivery_date', 'LIKE', "%$delivery_date%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        // dump($sort_by_date);
        $pagesize = session('pagesize');
        $data = $mod->orderBy('created_at', $sort_by_date)->paginate($pagesize);
        return view('invoice.index', compact('data', 'companies', 'stores', 'suppliers', 'company_id', 'store_id', 'supplier_id', 'reference_no', 'period', 'expiry_period', 'keyword', 'sort_by_date'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_invoice']);
        $user = Auth::user();  
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('invoice.create', compact('suppliers', 'products'));
    }

    public function save(Request $request){
        $request->validate([
            'reference_no'=>'required|string',
            'supplier'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => 'Please select a prouct.']);
        }

        // dd($data);
        $item = new Invoice();
        $item->reference_no = $data['reference_no'];
        $item->issue_date = $data['issue_date'];
        $item->due_date = $data['due_date'];
        $item->customers_vat = $data['customers_vat'];
        $item->delivery_date = $data['delivery_date'];
        $item->concerning_week = $data['concerning_week'];
        $item->shipment = $data['shipment'];
        $item->vessel = $data['vessel'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->origin = $data['origin'];
        $item->vat_amount = $data['vat_amount'];
        $item->total_to_pay = $data['total_to_pay'];
        $item->note = $data['note'];
        
        $item->save();

        if(isset($data['product_id']) && count($data['product_id']) > 0){

            for ($i=0; $i < count($data['product_id']); $i++) { 
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'amount' => $data['amount'][$i],
                    'surcharge_reduction' => $data['surcharge_reduction'][$i],
                    'total_amount' => $data['total_amount'][$i],
                    'itemable_id' => $item->id,
                    'itemable_type' => Invoice::class,
                ]);
            }
        }

        return redirect(route('invoice.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'purchase']); 
        $user = Auth::user();   
        $purchase = Purchase::find($id);        
        $suppliers = Supplier::all();
        $products = Product::all();
        $stores = Store::all();
        if($user->role->slug == 'user'){
            $stores = $user->company->stores;
        }
        return view('purchase.edit', compact('purchase', 'suppliers', 'stores', 'products'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'purchase']);    
        $purchase = Purchase::find($id);

        return view('purchase.detail', compact('purchase'));
    }

    public function update(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_number'=>'required|string',
            'store'=>'required',
            'supplier'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => 'Please select a prouct.']);
        }
        // dd($data);
        $item = Purchase::find($request->get("id"));
 
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->store_id = $data['store'];
        $store = Store::find($data['store']);
        $item->company_id = $store->company_id;
        $item->supplier_id = $data['supplier'];
        if($data['credit_days'] != ''){
            $item->credit_days = $data['credit_days'];
            $item->expiry_date = date('Y-m-d', strtotime("+".$data['credit_days']."days", strtotime($item->timestamp)));
        }
        $item->status = $data['status'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "purchase_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/purchase_images/'), $imageName);
            $item->attachment = 'images/uploaded/purchase_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = -1 * $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();

        if(isset($data['order_id']) && count($data['order_id']) > 0){
            for ($i=0; $i < count($data['order_id']); $i++) { 
                $order = Order::find($data['order_id'][$i]);
                $order_original_quantity = $order->quantity;
                $order->update([
                    'product_id' => $data['product_id'][$i],
                    'cost' => $data['cost'][$i],
                    'quantity' => $data['quantity'][$i],
                    'expiry_date' => $data['expiry_date'][$i],
                    'subtotal' => $data['subtotal'][$i],
                ]);
                if($order_original_quantity != $data['quantity'][$i]){
                    $store_product = StoreProduct::where('store_id', $data['store'])->where('product_id', $data['product_id'][$i])->first();                
                    $store_product->increment('quantity', $data['quantity'][$i]);
                    $store_product->decrement('quantity', $order_original_quantity);
                }
            }
        }
        
        return back()->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Purchase::find($id);
        $item->orders()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
