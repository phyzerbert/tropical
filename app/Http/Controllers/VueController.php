<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\SaleProforma;
use App\Models\Shipment;
use App\Models\Container;
use App\Models\Sale;
use App\Models\Item;

use App;

class VueController extends Controller
{
    
    public function get_products() {
        $products = Product::all();
        return response()->json($products);
    }

    public function get_product(Request $request) {
        $id = $request->get('id');

        $product = Product::find($id);

        return response()->json($product);
    }

    public function get_items(Request $request) {
        $id = $request->get('id');
        $type = $request->get('type');
        // dd($request->all());
        if($type == 'purchase'){
            $item = Purchase::find($id);
        }elseif($type == 'sale'){
            $item = Sale::find($id);
        }        
        $orders = $item->orders;
        return response()->json($orders);
    }

    public function get_data(Request $request){
        $id = $request->get('id');
        $type = $request->get('type');
        // dd($request->all());
        if($type == 'purchase'){
            $item = Purchase::find($id);
        }elseif($type == 'sale'){
            $item = Sale::find($id);
        }
        return response()->json($item);
    }

    public function get_first_product(Request $request){
        $item = Product::first();
        return response()->json($item);
    }

    public function get_autocomplete_products(Request $request){
        $keyword = $request->get('keyword');
        $data = Product::where('code', 'LIKE', "%$keyword%")->orWhere('name', 'LIKE', "%$keyword%")->get();
        return response()->json($data);
    }

    public function get_invoice(Request $request){
        $id = $request->get('id');
        $item = Invoice::find($id)->load('items');
        return response()->json($item);
    }

    public function get_proforma(Request $request){
        $id = $request->get('id');
        $item = Proforma::find($id)->load('items');
        return response()->json($item);
    }

    public function get_sale_proforma(Request $request){
        $id = $request->get('id');
        $item = SaleProforma::find($id)->load('items');
        return response()->json($item);
    }

    public function get_shipment(Request $request){
        $id = $request->get('id');
        $item = Shipment::find($id)->load('items');
        return response()->json($item);
    }

    public function get_container(Request $request){
        $id = $request->get('id');
        $item = Container::find($id);
        return response()->json($item);
    }

    public function get_received_quantity(Request $request){
        $id = $request->get('id');
        $item = PreOrderItem::find($id);
        $received_quantity = $item->purchased_items->sum('quantity');
        return response()->json($received_quantity);
    }

    public function get_sale(Request $request){
        $id = $request->get('id');
        $item = Sale::find($id)->load('items');
        // dump($item->items);
        return response()->json($item);
    }
    
}
