<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SaleProforma;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Item;
use App\Models\Payment;

use Auth;
use PDF;

class SaleProformaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'sale_proforma']);
        $customers = Customer::all();

        $mod = new SaleProforma();
        $reference_no = $customer_id = $period = $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }
        if ($request->get('customer_id') != ""){
            $customer_id = $request->get('customer_id');
            $mod = $mod->where('customer_id', $customer_id);
        }
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
            $mod = $mod->whereBetween('timestamp', [$from, $to]);
        }
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $customer_array = Customer::where('company', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword, $customer_array){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhereIn('customer_id', $customer_array)
                        ->orWhere('timestamp', 'LIKE', "%$keyword%")
                        ->orWhere('grand_total', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('timestamp', $sort_by_date)->paginate($pagesize);
        return view('sale_proforma.index', compact('data', 'customers', 'customer_id', 'reference_no', 'period', 'keyword', 'sort_by_date'));
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
            'reference_number'=>'required|string',
            'customer'=>'required',
        ]);

        $data = $request->all();
        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }

        // dd($data);
        $item = new SaleProforma();
        $item->user_id = Auth::user()->id;
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->customer_id = $data['customer'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/sale_images/'), $imageName);
            $item->attachment = 'images/uploaded/sale_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
        $item->save();

        if(isset($data['product_id']) && count($data['product_id']) > 0){

            for ($i=0; $i < count($data['product_id']); $i++) { 
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'amount' => $data['amount'][$i],
                    'total_amount' => $data['amount'][$i],
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
            'reference_number'=>'required|string',
            'customer'=>'required',
        ]);
        $data = $request->all();

        if(!isset($data['product_id']) ||  count($data['product_id']) == 0 || in_array(null, $data['product_id'])){
            return back()->withErrors(['product' => __('page.select_product')]);
        }
        // dd($data);
        $item = SaleProforma::find($request->get("id"));
 
        $item->timestamp = $data['date'].":00";
        $item->reference_no = $data['reference_number'];
        $item->customer_id = $data['customer'];
        $item->note = $data['note'];

        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "sale_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/sale_images/'), $imageName);
            $item->attachment = 'images/uploaded/sale_images/'.$imageName;
        }

        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];

        $item->shipping_string = $data['shipping_string'];
        $item->shipping = $data['shipping'];
        $item->returns = $data['returns'];
        
        $item->grand_total = $data['grand_total'];
        
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
                        'amount' => $data['amount'][$i],
                        'total_amount' => $data['amount'][$i],
                        'itemable_id' => $item->id,
                        'itemable_type' => SaleProforma::class,
                    ]);
                }else{
                    $order = Item::find($data['item_id'][$i]);
                    $order->update([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['amount'][$i],
                        'total_amount' => $data['amount'][$i],
                    ]);
                }
            }
        }
        
        return redirect(route('sale_proforma.index'))->with('success', __('page.updated_successfully'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'sale']);
        $sale = SaleProforma::find($id);

        return view('sale_proforma.detail', compact('sale'));
    }

    public function report($id){
        $sale_proforma = SaleProforma::find($id);
        $pdf = PDF::loadView('sale_proforma.report', compact('sale_proforma'));  
        return $pdf->download('customer_proforma_report_'.$sale_proforma->reference_no.'.pdf');
    }
    public function report_view($id){
        $sale_proforma = SaleProforma::find($id);
        $pdf = PDF::loadView('sale.report', compact('sale_proforma'));  
        // return $pdf->download('sale_report_'.$sale->reference_no.'.pdf');
        return view('sale_proforma.report', compact('sale'));
    }

    public function submit(Request $request, $id){
        config(['site.page' => 'sale_proforma']); 
        $sale_proforma = SaleProforma::find($id);
        if($sale_proforma->status == 1){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        return view('sale_proforma.submit', compact('sale_proforma'));
    }

    public function save_submit(Request $request){
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
        $item->attachment = $proforma->attachment;
        $item->timestamp = $data['date'].":00";
        $item->discount_string = $data['discount_string'];
        $item->discount = $data['discount'];
        $item->shipping_string = $data['shipping_string'];
        $item->shipping = $data['shipping'];
        $item->returns = $data['returns'];        
        $item->grand_total = $data['grand_total'];

        $item->sale_proforma_id = $proforma->id;
        $item->save();
        $proforma->update(['status' => 1]);

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

    public function delete($id){
        $item = SaleProforma::find($id);
        if($item->status == 1 || $item->sale){
            return back()->withErrors(['submitted' => 'This proforma has been already submitted.']);
        }
        $item->items()->delete();
        $item->payments()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
