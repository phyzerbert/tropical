<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Container;
use App\Models\Proforma;
use App\Models\Transaction;
use Carbon\Carbon;

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
    public function index(Request $request) {        
        config(['site.page' => 'home']);$now = Carbon::now();
        $period = '';
        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
        
            $chart_start = Carbon::createFromFormat('Y-m-d', $from);
            $chart_end = Carbon::createFromFormat('Y-m-d', $to);
        }else{
            $chart_start = Carbon::now()->startOfMonth();
            $chart_end = Carbon::now()->endOfMonth();
        }
        
        $key_array = $expense_array = $income_array = array();

        for ($dt=$chart_start; $dt < $chart_end; $dt->addDay()) {
            $key = $dt->format('Y-m-d');
            $key1 = $dt->format('M/d');
            array_push($key_array, $key1);
            $daily_expense = Transaction::where('type', 1)->whereDate('timestamp', $key)->sum('amount');
            $daily_incoming = Transaction::where('type', 2)->whereDate('timestamp', $key)->sum('amount');
            
            array_push($expense_array, $daily_expense);
            array_push($income_array, $daily_incoming);
        }

        return view('home', compact('key_array', 'expense_array', 'income_array', 'period'));
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
        $week_c = $week_d = $keyword = '';
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
        $pagesize = 15;
        if($request->pagesize != ''){
            $pagesize = $request->pagesize;
        }
        $data = $mod->orderBy('created_at', 'desc')->paginate($pagesize);
        return view('search.index', compact('data', 'week_c', 'week_d', 'keyword', 'search_params', 'pagesize'));
    }
}
