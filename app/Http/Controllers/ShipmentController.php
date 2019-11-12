<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Shipment;
use App\Models\Proforma;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Product;
use App\Models\Supplier;

class ShipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        config(['site.page' => 'shipment']);

        $mod = new Shipment();
        $keyword = '';
        $sort_by_date = 'desc';
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $proforma_array = Proforma::where('date', 'LIKE', "%$keyword%")->pluck('id');

            $mod = $mod->where(function($query) use($keyword){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhere('week_c', 'LIKE', "%$keyword%");
            });
        }
        if($request->sort_by_date != ''){
            $sort_by_date = $request->sort_by_date;
        }
        
        $pagesize = 15;
        if($request->get('pagesize') != ''){
            $pagesize = $request->get('pagesize');
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('created_at', $sort_by_date)->paginate($pagesize);
        return view('shipment.index', compact('data', 'keyword', 'sort_by_date', 'pagesize'));
    }

    public function detail(Request $request, $id){
        config(['site.page' => 'shipment']);
        $shipment = Shipment::find($id);
        return view('shipment.detail', compact('shipment'));
    }

    public function edit($id) {
        config(['site.page' => 'shipment']);
        $shipment = Shipment::find($id);
        return view('shipment.edit', compact('shipment'));
    }

    public function update(Request $request) {
        $request->validate([
            'invoice' => 'required',
            'week_c' => 'required',
            'product_name' => 'required',
        ]);
        $data = $request->all();
        $shipment = Shipment::find($request->id);
        $shipment->reference_no = $data['invoice'];
        $shipment->week_c = $data['week_c'];
        $shipment->total_to_pay = $data['total_to_pay'];
        $shipment->save();

        $shipment_items = $shipment->items->pluck('id')->toArray();
        $diff_items = array_diff($shipment_items, $data['item_id']);
        foreach ($diff_items as $key => $value) {
            Item::find($value)->delete();
        }

        if(isset($data['item_id']) && count($data['item_id']) > 0){
            for ($i=0; $i < count($data['product_id']); $i++) { 
                if(!$data['item_id'][$i]){
                    Item::create([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                        'itemable_id' => $shipment->id,
                        'itemable_type' => Shipment::class,
                    ]);
                }else{
                    $order = Item::find($data['item_id'][$i]);
                    $order->update([
                        'product_id' => $data['product_id'][$i],
                        'price' => $data['price'][$i],
                        'quantity' => $data['quantity'][$i],
                        'amount' => $data['total_amount'][$i],
                        'total_amount' => $data['total_amount'][$i],
                        'itemable_id' => $shipment->id,
                        'itemable_type' => Shipment::class,
                    ]);
                }
            }
        }
         
        return back()->with('success', __('page.updated_successfully'));        
    }

    public function delete($id){
        $item = Shipment::find($id);
        if($item->proforma){
            return back()->withErrors(['product' => 'You can not delete this shipment.']);
        }
        $item->items()->delete();
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function receive(Request $request, $id){
        config(['site.page' => 'shipment']); 
        $shipment = Shipment::find($id);
        if($shipment->is_recieved == 1){
            return back()->withErrors(['received' => 'This shipment has been already received.']);
        }
        return view('shipment.receive', compact('shipment'));
    }

    public function save_receive(Request $request){
        $request->validate([
            'invoice'=>'required|string',
        ]);
        $data = $request->all();
        // dd($data);
        $shipment = Shipment::find($request->get('id'));
        $proforma = $shipment->proforma;
        if($shipment->is_received == 1){
            return back()->withErrors(['received' => 'This shipment has been already received.']);
        }
        $item = new Invoice();
        $item->reference_no = $data['invoice'];
        $item->supplier_id = $proforma->supplier_id;
        $item->issue_date = $proforma->date;
        $item->due_date = $proforma->due_date;
        $item->customers_vat = $proforma->customers_vat;
        $item->concerning_week = $proforma->concerning_week;
        $item->vessel = $proforma->vessel;
        $item->port_of_discharge = $proforma->port_of_discharge;
        $item->origin = $proforma->origin;
        $item->total_to_pay = $data['total_to_pay'];
        $item->proforma_id = $proforma->id;
        $item->save();
        $shipment->update(['is_received' => 1]);

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
        return redirect(route('shipment.index'))->with("success", __('page.received_successfully'));
    }
}
