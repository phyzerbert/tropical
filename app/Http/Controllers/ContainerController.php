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
        ]);

        $data = $request->all();

        $item = Container::find($request->id);
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
        
        return back()->with('success', __('page.updated_successfully'));
    }

    public function delete($id){
        $item = Container::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
