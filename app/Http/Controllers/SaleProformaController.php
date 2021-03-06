<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SaleProforma;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Item;
use App\Models\Payment;
use App\Models\SaleShipment;

use Auth;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class SaleProformaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'sale_proforma']);
        $customers = Customer::all();

        $mod = new SaleProforma();
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
        $pagesize = 15;
        if($request->get('pagesize') != ''){
            $pagesize = $request->get('pagesize');
        }
        $data = $mod->orderBy('date', $sort_by_date)->paginate($pagesize);
        return view('sale_proforma.index', compact('data', 'supplier_id', 'reference_no', 'week_c', 'week_d', 'keyword', 'sort_by_date', 'pagesize'));
    }
    
    public function create(Request $request){
        config(['site.page' => 'add_sale_proforma']);
        $customers = Customer::all();
        $products = Product::all();
        return view('sale_proforma.create', compact('customers', 'products'));
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
        $item = new SaleProforma();
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
                    'itemable_type' => SaleProforma::class,
                ]);
            }
        }       

        return redirect(route('sale_proforma.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'sale_proforma']);
        $sale_proforma = SaleProforma::find($id);        
        $customers = Customer::all();
        $products = Product::all();
        return view('sale_proforma.edit', compact('sale_proforma', 'customers', 'products'));
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
        $item = SaleProforma::find($request->get("id"));
 
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
                        'itemable_type' => SaleProforma::class,
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
        
        return redirect(route('sale_proforma.index'))->with('success', __('page.updated_successfully'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'sale_proforma']);
        $sale = SaleProforma::find($id);

        return view('sale_proforma.detail', compact('sale'));
    }

    public function report($id){
        $sale_proforma = SaleProforma::find($id);
        $pdf = PDF::loadView('sale_proforma.report', compact('sale_proforma'));
        return $pdf->download('customer_proforma_report_'.$sale_proforma->reference_no.'.pdf');
    }

    public function email($id){
        $sale_proforma = SaleProforma::find($id);
        $pdf = PDF::loadView('sale_proforma.report', compact('sale_proforma'));  
        if(filter_var($sale_proforma->customer->email, FILTER_VALIDATE_EMAIL)){
            $to_email = $sale_proforma->customer->email;
            Mail::to($to_email)->send(new InvoiceMail($pdf, 'Customer Proforma Invoice')); 
            return back()->with('success', 'Email is sent successfully');
        }else{
            return back()->withErrors('email', 'Customer email address is invalid.');
        }
    }

    public function report_view($id){
        $sale_proforma = SaleProforma::find($id);
        $pdf = PDF::loadView('sale_proforma.report', compact('sale_proforma'));  
        // return $pdf->download('sale_report_'.$sale->reference_no.'.pdf');
        return view('sale_proforma.report', compact('sale_proforma'));
    }

    public function submit(Request $request, $id){
        config(['site.page' => 'sale_proforma']); 
        $sale_proforma = SaleProforma::find($id);
        if($sale_proforma->status == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        return view('sale_proforma.submit', compact('sale_proforma'));
    }

    public function save_receive(Request $request){
        $request->validate([
            'proforma'=>'required|string',
            'date'=>'required',
        ]);
        $data = $request->all();
        // dd($data);
        $proforma = SaleProforma::find($request->get('id'));
        if($proforma->status == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        $item = new Sale();
        $item->reference_no = $data['proforma'];
        $item->customer_id = $proforma->customer_id;
        $item->date = $proforma->date;
        $item->due_date = $proforma->due_date;
        $item->customers_vat = $proforma->customers_vat;
        $item->concerning_week = $proforma->concerning_week;
        $item->vessel = $proforma->vessel;
        $item->port_of_discharge = $proforma->port_of_discharge;
        $item->port_of_charge = $proforma->port_of_charge;
        $item->origin = $proforma->origin;
        $item->week_c = $proforma->week_c;
        $item->week_d = $proforma->week_d;
        $item->total_to_pay = $data['grand_total'];
        $item->sale_proforma_id = $proforma->id;
        $item->save();
        $proforma->update(['is_submitted' => 1]);

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

        foreach ($proforma->payments as $payment) {
            $payment->sale_id = $item->id;
            $payment->save();
        }
        return redirect(route('sale_proforma.index'))->with("success", __('page.submitted_successfully'));
    }

    public function save_submit(Request $request){
        $request->validate([
            'proforma'=>'required|string',
            'week_c'=>'required',
        ]);
        $data = $request->all();
        $proforma = SaleProforma::find($request->get('id'));
        if($proforma->is_submitted == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        $item = new SaleShipment();
        $item->reference_no = $data['proforma'];
        $item->week_c = $data['week_c'];
        $item->sale_proforma_id = $proforma->id;
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
                    'itemable_type' => SaleShipment::class,
                ]);
            }
        }
        return redirect(route('sale_proforma.index'))->with("success", __('page.submitted_successfully'));
    }

    public function delete($id){
        $item = SaleProforma::find($id);
        if($item->sale){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        $item->items()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
