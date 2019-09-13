<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Container;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect(route('invoice.index'));
    }

    
    public function set_pagesize(Request $request){
        $pagesize = $request->get('pagesize');
        if($pagesize == '') $pagesize = 100000;
        $request->session()->put('pagesize', $pagesize);
        return back();
    }

    public function search(Request $request){
        config(['site.page' => 'search']);
        $mod = new Container();
        $week_c = $week_d = '';
        $identification = $container = $booking = $bl = $shipping_company = 'yes';
        $temperature = $damper = $type_of_merchandise = $fruit_loading_date = $ship_departure_date = 'yes';
        $estimated_date = $agency = $company = $dock = 'yes';
        $search_params = array();
        if($request->has('search_params')){
            $search_params = $request->get('search_params');
        }
        if ($request->get('week_c') != ""){
            $week_c = $request->get('week_c');
            $mod = $mod->where('week_c', $week_c);
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            $mod = $mod->where('week_d', $week_d);
        }
        $pagesize = session('pagesize');
        $data = $mod->orderBy('created_at', 'desc')->paginate($pagesize);
        return view('search.index', compact('data', 'week_c', 'week_d', 'search_params'));
    }
}
