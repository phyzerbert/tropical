<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Shipment;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Supplier;

use Auth;
use PDF;

class ProformaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'proforma']);
        $user = Auth::user();
        $suppliers = Supplier::all();

        $mod = new Proforma();
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
            if(strpos($week_c, ",") !== false) {
                $week_c_array = explode(',', $week_c);
                $mod = $mod->whereIn('week_c', $week_c_array);
            } else {
                $mod = $mod->where('week_c', $week_c);
            }            
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            if(strpos($week_d, ",") !== false) {
                $week_d_array = explode(',', $week_d);
                $mod = $mod->whereIn('week_d', $week_d_array);
            } else {
                $mod = $mod->where('week_d', $week_d);
            } 
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
        
        $pagesize = 15;
        if($request->get('pagesize') != ''){
            $pagesize = $request->get('pagesize');
        }
        
        $data = $mod->orderBy('date', $sort_by_date)->paginate($pagesize);
        return view('proforma.index', compact('data', 'suppliers', 'supplier_id', 'reference_no', 'week_c', 'week_d', 'keyword', 'sort_by_date', 'pagesize'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_proforma']);
        $user = Auth::user();  
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('proforma.create', compact('suppliers', 'products'));
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
        $item = new Proforma();
        $item->reference_no = $data['reference_no'];
        $item->date = $data['date'];
        $item->supplier_id = $data['supplier'];
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
            $imageName = "proforma_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/proforma_images/'), $imageName);
            $item->image = 'images/uploaded/proforma_images/'.$imageName;
        }
        $item->save();

        if(isset($data['product_id']) && count($data['product_id']) > 0){
            for ($i=0; $i < count($data['product_id']); $i++) { 
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'total_amount' => $data['total_amount'][$i],
                    'itemable_id' => $item->id,
                    'itemable_type' => Proforma::class,
                ]);
            }
        }

        return redirect(route('proforma.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);        
        $suppliers = Supplier::all();
        return view('proforma.edit', compact('invoice', 'suppliers'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);
        return view('proforma.detail', compact('invoice'));
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
        $item = Proforma::find($request->get("id"));
 
        $item->reference_no = $data['reference_no'];
        $item->date = $data['date'];
        $item->supplier_id = $data['supplier'];
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
            $imageName = "proforma_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/proforma_images/'), $imageName);
            $item->image = 'images/uploaded/proforma_images/'.$imageName;
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
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                        'itemable_id' => $item->id,
                        'itemable_type' => Proforma::class,
                    ]);
                }else{
                    $order = Item::find($data['item_id'][$i]);
                    $order->update([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                        'itemable_id' => $item->id,
                        'itemable_type' => Proforma::class,
                    ]);
                }
            }
        }        
        return back()->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Proforma::find($id);
        if($item->containers->isNotEmpty()){
            return back()->withErrors(['product' => 'You can not delete this pro-forma.']);
        }
        $item->items()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function submit(Request $request, $id){
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);
        if($invoice->is_submitted == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        return view('proforma.submit', compact('invoice'));
    }

    public function save_submit(Request $request){
        $request->validate([
            'invoice'=>'required|string',
            'week_c'=>'required',
        ]);
        $data = $request->all();
        $proforma = Proforma::find($request->get('id'));
        if($proforma->is_submitted == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        $item = new Shipment();
        $item->reference_no = $data['invoice'];
        $item->week_c = $data['week_c'];
        $item->proforma_id = $proforma->id;
        $item->total_to_pay = $data['total_to_pay'];
        $item->save();
        $proforma->update(['is_submitted' => 1]);

        if(isset($data['product_id']) && count($data['product_id']) > 0){
            for ($i=0; $i < count($data['product_id']); $i++) {
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'total_amount' => $data['total_amount'][$i],
                    'itemable_id' => $item->id,
                    'itemable_type' => Shipment::class,
                ]);
            }
        }
        return redirect(route('proforma.index'))->with("success", __('page.submitted_successfully'));
    }

    public function container(Request $request, $id){
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);
        $mod = $invoice->containers();
        $keyword = '';
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;

            $proforma_array = Proforma::where('reference_no', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $proforma_array){
                return $query->whereIn('proforma_id', $proforma_array)
                        ->orWhere('identification_or_nit', 'LIKE', "%$keyword%")
                        ->orWhere('container', 'LIKE', "%$keyword%")
                        ->orWhere('booking', 'LIKE', "%$keyword%")
                        ->orWhere('bl', 'LIKE', "%$keyword%")
                        ->orWhere('temperatura', 'LIKE', "%$keyword%")
                        ->orWhere('damper', 'LIKE', "%$keyword%")
                        ->orWhere('booking', 'LIKE', "%$keyword%")
                        ->orWhere('shipping_company', 'LIKE', "%$keyword%")
                        ->orWhere('fruit_loading_date', 'LIKE', "%$keyword%")
                        ->orWhere('ship_departure_date', 'LIKE', "%$keyword%")
                        ->orWhere('type_of_merchandise', 'LIKE', "%$keyword%")
                        ->orWhere('port_of_discharge', 'LIKE', "%$keyword%")
                        ->orWhere('estimated_date', 'LIKE', "%$keyword%")
                        ->orWhere('agency', 'LIKE', "%$keyword%")
                        ->orWhere('company', 'LIKE', "%$keyword%")
                        ->orWhere('dock', 'LIKE', "%$keyword%")
                        ->orWhere('vgm', 'LIKE', "%$keyword%");
            });
        }

        $data = $mod->get();
        return view('proforma.container', compact('data', 'invoice', 'keyword'));
    }
}