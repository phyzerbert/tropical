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
        $identification = $container = $booking = $bl = 'yes';
        if ($request->get('week_c') != ""){
            $week_c = $request->get('week_c');
            $mod = $mod->where('week_c', $week_c);
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            $mod = $mod->where('week_d', $week_d);
        }
        if ($request->get('identification') != ""){
            $identification = $request->get('identification');
        }
        if ($request->get('container') != ""){
            $container = $request->get('container');
        }
        if ($request->get('booking') != ""){
            $booking = $request->get('booking');
        }
        if ($request->get('bl') != ""){
            $bl = $request->get('bl');
        }

        $data = $mod->orderBy('created_at', 'desc')->paginate(20);
        return view('search.index', compact('data', 'week_c', 'week_d', 'identification', 'container', 'booking', 'bl'));
    }
}
