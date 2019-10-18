<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        config(['site.page' => 'transaction']);
        $categories = Category::all();
        $mod = new Transaction();
        $total = array();
        $category = $keyword = $period = '';
        if($request->keyword != ''){
            $keyword = $request->keyword;
            $mod = $mod->where('reference_no', 'like', "%$keyword%")
                ->orWhere('note', 'like', "%$keyword%")
                ->orWhere('timestamp', 'like', "%$keyword%");
        }
        if($request->category != ''){
            $category = $request->category;
            $mod = $mod->where('category_id', $category);
        }
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10)." 00:00:00";
            $to = substr($period, 14, 10)." 23:59:59";
            if($from == $to){
                $mod = $mod->whereDate('timestamp', $to);
            }else{                
                $mod = $mod->whereBetween('timestamp', [$from, $to]);
            } 
        }
        $pagesize = 15;
        if($request->get('pagesize') != ''){
            $pagesize = $request->get('pagesize');
        }
        $data = $mod->orderBy('timestamp', 'desc')->paginate($pagesize);
        $collection = $mod->get();
        $total['expense'] = $collection->where('type', 1)->sum('amount');
        $total['incoming'] = $collection->where('type', 2)->sum('amount');
        return view('transaction.index', compact('data', 'categories', 'total', 'category', 'keyword', 'period', 'pagesize'));
    }

    public function create(Request $request){
        $request->validate([
            'reference_no' => 'required',
            'date' => 'required',
            'amount' => 'required',
        ]);
        $item = new Transaction();
        $item->reference_no = $request->get("reference_no");
        $item->timestamp = $request->get("date") . ":00";
        $item->type = $request->get("type");
        $item->amount = $request->get("amount");
        $item->category_id = $request->get("category");
        $item->note = $request->get("note");
        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "transaction_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/transaction_images/'), $imageName);
            $item->attachment = 'images/uploaded/transaction_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.created_successfully'));
    }

    public function edit(Request $request){
        $request->validate([
            'date'=>'required',
            'amount'=>'required',
        ]);
        // dd($request->all());
        $item = Transaction::find($request->get("id"));
        $item->reference_no = $request->get("reference_no");
        $item->timestamp = $request->get("date") . ":00";
        $item->amount = $request->get("amount");
        $item->category_id = $request->get("category");
        $item->note = $request->get("note");
        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "transaction_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/transaction_images/'), $imageName);
            $item->attachment = 'images/uploaded/transaction_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }


    public function delete($id){
        $item = Transaction::find($id);
        $item->delete();       
        return back()->with('success', __('page.deleted_successfully'));
    }
}
