<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Item;

use Auth;

class SaleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'sale']);
        $customers = Customer::all();

        $mod = new Sale();
        $reference_no = $supplier_id = $week_c = $week_d = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('supplier_id') != ""){
            $supplier_id = $request->get('supplier_id');
            $mod = $mod->where('supplier_id', $supplier_id);
        }
        if ($request->get('week_c') != ""){
            $week_c = $request->get('week_c');
            $mod = $mod->where('week_c', $week_c);
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            $mod = $mod->where('week_d', $week_d);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $supplier_array = Supplier::where('company', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $supplier_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('supplier_id', $supplier_array)
                        ->orWhere('date', 'LIKE', "%$keyword%")
                        ->orWhere('due_date', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('date', $sort_by_date)->paginate($pagesize);
        return view('sale.index', compact('data', 'suppliers', 'supplier_id', 'reference_no', 'week_c', 'week_d', 'keyword', 'sort_by_date'));
    }
    
    public function create(Request $request){
        config(['site.page' => 'add_sale']);
        $customers = Customer::all();
        $products = Product::all();
        return view('sale.create', compact('customers', 'products'));
    }

    public function save(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_no'=>'required|string',
            'customer'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }

        // dd($data);
        $item = new Sale();
        $item->reference_no = $data['reference_no'];
        $item->date = $data['date'];
        $item->customer_id = $data['customer'];
        $item->due_date = $data['due_date'];
        $item->customers_vat = $data['customers_vat'];
        $item->concerning_week = $data['concerning_week'];
        $item->vessel = $data['vessel'];
        $item->port_of_charge = $data['port_of_charge'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->origin = $data['origin'];
        $item->week_c = $data['week_c'];
        $item->week_d = $data['week_d'];
        $item->total_to_pay = $data['total_to_pay'];
        $item->note = $data['note'];

        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/sale_images/'), $imageName);
            $item->image = 'images/uploaded/sale_images/'.$imageName;
        }

        $item->save();

        if(isset($data['product_id']) && count($data['product_id']) > 0){
            for ($i=0; $i < count($data['product_id']); $i++) { 
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'amount' => $data['total_amount'][$i],
                    'total_amount' => $data['total_amount'][$i],
                    'itemable_id' => $item->id,
                    'itemable_type' => Sale::class,
                ]);
            }
        }       

        return redirect(route('sale.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'sale_list']);
        $sale = Sale::find($id);        
        $customers = Customer::all();
        $products = Product::all();

        return view('sale.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_no'=>'required|string',
            'customer'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }
        // dd($data);
        $item = Sale::find($request->get("id"));
 
        $item->reference_no = $data['reference_no'];
        $item->date = $data['date'];
        $item->customer_id = $data['customer'];
        $item->due_date = $data['due_date'];
        $item->customers_vat = $data['customers_vat'];
        $item->concerning_week = $data['concerning_week'];
        $item->vessel = $data['vessel'];
        $item->port_of_charge = $data['port_of_charge'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->origin = $data['origin'];
        $item->week_c = $data['week_c'];
        $item->week_d = $data['week_d'];
        $item->total_to_pay = $data['total_to_pay'];
        $item->note = $data['note'];

        if($request->has("image")){
            $picture = request()->file('image');
            $imageName = "sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/sale_images/'), $imageName);
            $item->image = 'images/uploaded/sale_images/'.$imageName;
        }
        
        $item->save();

        $sale_items = $item->items->pluck('id')->toArray();
        $diff_items = array_diff($sale_items, $data['item_id']);
        foreach ($diff_items as $key => $value) {
            Item::find($value)->delete();
        }        
        
        if(isset($data['item_id']) && count($data['item_id']) > 0){
            for ($i=0; $i < count($data['item_id']); $i++) {
                if($data['item_id'][$i] == ''){
                    Item::create([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                        'itemable_id' => $item->id,
                        'itemable_type' => Sale::class,
                    ]);
                }else{
                    $order = Item::find($data['item_id'][$i]);
                    $order->update([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                    ]);
                }
            }
        }
        
        return redirect(route('sale.index'))->with('success', __('page.updated_successfully'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'sale']);
        $sale = Sale::find($id);

        return view('sale.detail', compact('sale'));
    }

    public function report($id){
        $sale = ProductSale::find($id);
        $pdf = PDF::loadView('sale.report', compact('sale'));  
        return $pdf->download('sale_report_'.$sale->reference_no.'.pdf');
    }
    public function report_view($id){
        $sale = ProductSale::find($id);
        $pdf = PDF::loadView('sale.report', compact('sale'));  
        // return $pdf->download('sale_report_'.$sale->reference_no.'.pdf');
        return view('sale.report', compact('sale'));
    }

    public function delete($id){
        $item = Sale::find($id);
        $item->items()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
