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
        $week_c = $week_d = $keyword = '';
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

        $data = $mod->orderBy('created_at', 'desc')->paginate(15);

        return view('container.index', compact('data', 'week_c', 'week_d', 'keyword'));
    }

    public function create(Request $request){
        config(['site.page' => 'add_container']);
        $proformas = Proforma::all();
        return view('container.create', compact('proformas'));
    }

    public function save(Request $request){
        $request->validate([
            'proforma'=>'required',
            'estimated_date' => 'numeric',
        ]);

        $data = $request->all();
        $item = new Container();
        $item->proforma_id = $data['proforma'];
        $item->identification_or_nit = $data['identification_or_nit'];
        $item->week_c = $data['week_c'];
        $item->week_d = $data['week_d'];
        $item->container = $data['container'];
        $item->booking = $data['booking'];
        $item->bl = $data['bl'];
        $item->shipping_company = $data['shipping_company'];
        $item->temperatura = $data['temperatura'];
        $item->damper = $data['damper'];
        $item->type_of_merchandise = $data['type_of_merchandise'];
        $item->fruit_loading_date = $data['fruit_loading_date'];
        $item->ship_departure_date = $data['ship_departure_date'];
        $item->estimated_date = $data['estimated_date'];
        $item->agency = $data['agency'];
        $item->company = $data['company'];
        $item->dock = $data['dock'];

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
        $container = Container::find($id);        
        $proformas = Proforma::all();
        return view('container.edit', compact('container', 'proformas'));
    }

    public function detail(Request $request, $id){    
        config(['site.page' => 'container']); 
        $container = Container::find($id);
        return view('container.detail', compact('container'));
    }

    public function update(Request $request){
        $request->validate([
            'proforma'=>'required',
            'estimated_date' => 'numeric',
        ]);

        $data = $request->all();

        $item = Container::find($request->id);
        $item->proforma_id = $data['proforma'];
        $item->identification_or_nit = $data['identification_or_nit'];
        $item->week_c = $data['week_c'];
        $item->week_d = $data['week_d'];
        $item->container = $data['container'];
        $item->booking = $data['booking'];
        $item->bl = $data['bl'];
        $item->shipping_company = $data['shipping_company'];
        $item->temperatura = $data['temperatura'];
        $item->damper = $data['damper'];
        $item->type_of_merchandise = $data['type_of_merchandise'];
        $item->fruit_loading_date = $data['fruit_loading_date'];
        $item->ship_departure_date = $data['ship_departure_date'];
        $item->estimated_date = $data['estimated_date'];
        $item->agency = $data['agency'];
        $item->company = $data['company'];
        $item->dock = $data['dock'];

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
        
        return back()->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Container::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function search_by_bl(Request $request){
        config(['site.page' => 'container_bl']);
        
        $mod = new Container();
        $bl = '';
        if ($request->get('bl') != ""){
            $bl = $request->get('bl');
            $mod = $mod->where('bl', $bl);
        }

        $data = $mod->orderBy('created_at', 'desc')->paginate(15);

        return view('container.bl', compact('data', 'bl'));
    }

    public function search_by_booking(Request $request){
        config(['site.page' => 'container_booking']);
        
        $mod = new Container();
        $booking = '';
        if ($request->get('booking') != ""){
            $booking = $request->get('booking');
            $mod = $mod->where('booking', $booking);
        }

        $data = $mod->orderBy('created_at', 'desc')->paginate(15);

        return view('container.booking', compact('data', 'booking'));
    }
}
