<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Proforma;
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
        $reference_no = $supplier_id = $keyword = '';
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
                        ->orWhere('date', 'LIKE', "%$keyword%")
                        ->orWhere('due_date', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        // dump($sort_by_date);
        $pagesize = session('pagesize');
        $data = $mod->orderBy('created_at', $sort_by_date)->paginate($pagesize);
        return view('proforma.index', compact('data', 'suppliers', 'supplier_id', 'reference_no', 'keyword', 'sort_by_date'));
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
        $item->brand = $data['brand'];
        $item->vessel = $data['vessel'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->origin = $data['origin'];
        $item->total_to_pay = $data['total_to_pay'];
        $item->note = $data['note'];
        
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
        $item->brand = $data['brand'];
        $item->vessel = $data['vessel'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->origin = $data['origin'];
        $item->total_to_pay = $data['total_to_pay'];
        $item->note = $data['note'];
        
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
        $item->items()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function receive(Request $request, $id){
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);
        if($invoice->is_received == 1){
            return back()->withErrors(['received' => 'This proforma has been already received.']);
        }
        return view('proforma.receive', compact('invoice'));
    }

    public function save_receive(Request $request){
        $request->validate([
            'invoice'=>'required|string',
            'shipment'=>'required',
        ]);
        $data = $request->all();
        $proforma = Proforma::find($request->get('id'));
        if($proforma->is_received == 1){
            return back()->withErrors(['received' => 'This proforma has been already received.']);
        }
        $item = new Invoice();
        $item->reference_no = $data['invoice'];
        $item->issue_date = $proforma->date;
        $item->supplier_id = $proforma->supplier_id;
        $item->due_date = $proforma->due_date;
        $item->customers_vat = $proforma->customers_vat;
        $item->concerning_week = $proforma->concerning_week;
        $item->shipment = $data['shipment'];
        $item->vessel = $proforma->vessel;
        $item->port_of_discharge = $proforma->port_of_discharge;
        $item->origin = $proforma->origin;
        $item->total_to_pay = $proforma->total_to_pay;
        $item->note = $proforma->note; 
        $item->proforma_id = $proforma->id;       
        $item->save();
        $proforma->update(['is_received' => 1]);

        if(isset($data['product_id']) && count($data['product_id']) > 0){
            for ($i=0; $i < count($data['product_id']); $i++) {
                Item::create([
                    'product_id' => $data['product_id'][$i],
                    'price' => $data['price'][$i],
                    'quantity' => $data['quantity'][$i],
                    'total_amount' => $data['total_amount'][$i],
                    'itemable_id' => $item->id,
                    'itemable_type' => Invoice::class,
                ]);
            }
        }

        foreach ($proforma->payments as $payment) {
            $payment->invoice_id = $item->id;
            $payment->save();
        }
        return redirect(route('proforma.index'))->with("success", __('page.received_successfully'));
    }

    public function container(Request $request, $id){
        config(['site.page' => 'proforma']); 
        $invoice = Proforma::find($id);
        $data = $invoice->containers;
        return view('proforma.container', compact('data', 'invoice'));
    }
}