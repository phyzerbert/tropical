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
        $week_c = $week_d = $supplier_id = $period = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('week_c') != ""){
            $week_c = $request->get('week_c');
            if(strpos($week_c, ",") !== false) {
                $week_c_array = explode(',', $week_c);
                $proforma_array = Proforma::whereIn('week_c', $week_c_array)->pluck('id');
            } else {
                $proforma_array = Proforma::where('week_c', $week_c)->pluck('id');
            }            
            $mod = $mod->whereIn('proforma_id', $proforma_array);
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            if(strpos($week_d, ",") !== false) {
                $week_d_array = explode(',', $week_d);
                $proforma_array = Proforma::whereIn('week_d', $week_d_array)->pluck('id');
            } else {
                $proforma_array = Proforma::where('week_d', $week_d)->pluck('id');
            } 
            $mod = $mod->whereIn('proforma_id', $proforma_array);
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
                        ->orWhere('delivery_date', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = 15;
        if($request->get('pagesize') != ''){
            $pagesize = $request->get('pagesize');
        }
        $data = $mod->orderBy('issue_date', $sort_by_date)->paginate($pagesize);
        return view('invoice.index', compact('data', 'suppliers', 'supplier_id', 'week_c', 'week_d', 'period', 'keyword', 'sort_by_date', 'pagesize'));
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
        $item->supplier_id = $data['supplier'];
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

        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "invoice_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/invoice_images/'), $imageName);
            $item->image = 'images/uploaded/invoice_images/'.$imageName;
        }
        
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
        config(['site.page' => 'invoice']); 
        $invoice = Invoice::find($id);        
        $suppliers = Supplier::all();
        return view('invoice.edit', compact('invoice', 'suppliers'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'invoice']); 
        $invoice = Invoice::find($id);
        return view('invoice.detail', compact('invoice'));
    }

    public function update(Request $request){
        $request->validate([
            'reference_no'=>'required|string',
            'supplier'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => 'Please select a prouct.']);
        }
        // dd($data);
        $item = Invoice::find($request->get("id"));
 
        $item->reference_no = $data['reference_no'];
        $item->issue_date = $data['issue_date'];
        $item->due_date = $data['due_date'];
        $item->supplier_id = $data['supplier'];
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
        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "invoice_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/invoice_images/'), $imageName);
            $item->image = 'images/uploaded/invoice_images/'.$imageName;
        }
        $item->save();

        $invoice_items = $item->items->pluck('id')->toArray();
        $diff_items = array_diff($invoice_items, $data['item_id']);
        foreach ($diff_items as $key => $value) {
            Item::find($value)->delete();
        }

        if(isset($data['item_id']) && count($data['item_id']) > 0){

            for ($i=0; $i < count($data['product_id']); $i++) { 
                if($data['item_id'][$i] == ''){
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
                }else{
                    $order = Item::find($data['item_id'][$i]);
                    $order->update([
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
        } 
        
        return back()->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Invoice::find($id);
        $item->items()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
