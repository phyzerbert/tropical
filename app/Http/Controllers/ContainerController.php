<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proforma;
use App\Models\Container;
use App\Imports\ContainerImport;

use Excel;

class ContainerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        ini_set('memory_limit', '-1');
    }

    public function index(Request $request) {
        config(['site.page' => 'container']);
        
        $mod = new Container();
        $keyword = '';
        if($request->keyword != ''){

        }

        $data = $mod->orderBy('created_at', 'desc')->paginate(15);

        return view('container.index', compact('data', 'keyword'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_container']);
        $proformas = Proforma::all();
        return view('container.create', compact('proformas'));
    }

    public function save(Request $request){
        $request->validate([
            'proforma'=>'required',
        ]);

        $data = $request->all();

        $item = new Container();
        $item->proforma_id = $data['proforma'];
        $item->contenedor = $data['contenedor'];
        $item->precinto = $data['precinto'];
        $item->temperatura = $data['temperatura'];
        $item->damper = $data['damper'];
        $item->booking = $data['booking'];
        $item->port_of_discharge = $data['port_of_discharge'];
        $item->fetcha = $data['fetcha'];
        $item->embarcadero = $data['embarcadero'];
        $item->tipo_de_mercancia = $data['tipo_de_mercancia'];
        $item->agencia_aduanera = $data['agencia_aduanera'];
        $item->company_or_person = $data['company_or_person'];
        $item->total_container = $data['total_container'];
        $item->peso_carga = $data['peso_carga'];
        $item->tara = $data['tara'];
        $item->vgm = $data['vgm'];
        $product_list = array();
        for ($i=0; $i < count($data['product_id']); $i++) { 
            $product_list[$data['product_id'][$i]] = $data['quantity'][$i];
        }
        $item->product_list = json_encode($product_list);
        $item->save();
        return redirect(route('container.index'))->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request, $id){    
        config(['site.page' => 'container']); 
        $container = Invoice::find($id);        
        $suppliers = Supplier::all();
        return view('container.edit', compact('container', 'suppliers'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'container']); 
        $container = Invoice::find($id);
        return view('container.detail', compact('container'));
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
