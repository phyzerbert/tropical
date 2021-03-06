<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Container;

use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        config(['site.page' => 'product']);
        $mod = new Product();
        $keyword = '';
        if($request->keyword != ''){
            $keyword = $request->keyword;
            $mod = $mod->where('code', 'like', "%$keyword%")
                ->orWhere('name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        }
        $data = $mod->orderBy('created_at', 'desc')->paginate(12);
        return view('product.index', compact('data', 'keyword'));
    }

    public function edit(Request $request){
        $request->validate([
            'code'=>'required',
            'name'=>'required|string',
        ]);
        // dd($request->all());
        $item = Product::find($request->get("id"));
        $item->code = $request->get("code");
        $item->name = $request->get("name");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = $request->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path("images/uploaded/product_images"), $imageName);
            $imageName = "images/uploaded/product_images/".$imageName;
            $img = Image::make($imageName)->resize(400, 400);
            $img->save($imageName);
            $item->image = $imageName;
        }
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }

    public function create(Request $request){
        $request->validate([
            'code'=>'required|string',
            'name'=>'required|string',
        ]);
        $item = new Product();
        $item->code = $request->get("code");
        $item->name = $request->get("name");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = $request->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path("images/uploaded/product_images"), $imageName);
            $imageName = "images/uploaded/product_images/".$imageName;
            $img = Image::make($imageName)->resize(400, 400);
            $img->save($imageName);
            $item->image = $imageName;
        }
        $item->save();
        return back()->with('success', __('page.created_successfully'));
    }

    public function delete($id){
        $item = Product::find($id);
        if($item->items->isNotEmpty()){
            return back()->withErrors(['product' => 'You can not delete this product.']);
        }
        $item->delete();       
        return back()->with('success', __('page.deleted_successfully'));
    }

    public function produce_create(Request $request){
        $request->validate([
            'code'=>'required',
            'name'=>'required',
            'image' => 'required|file',
        ]);
        $item = new Product();
        $item->code = $request->get("code");
        $item->name = $request->get("name");
        $item->description = $request->get("description");
        if($request->has("image")){
            $picture = $request->file('image');
            $imageName = "product_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path("images/uploaded/product_images"), $imageName);
            $imageName = "images/uploaded/product_images/".$imageName;
            $img = Image::make($imageName)->resize(400, 400);
            $img->save($imageName);
            $item->image = $imageName;
        }
        $item->save();
        return response()->json($item);
    }

    public function stock($id) {
        $product = Product::find($id);
        $income = 0;
        $expense = $product->items()->where('itemable_type', 'App\Models\Sale')->sum('quantity');

        $containers = Container::all();
        foreach ($containers as $container) {
            $income += $container->product_quantity($id);
        }

        $quantity = $income - $expense;

        dump($expense);
        dump($income);
        dump($quantity);
    }
}
