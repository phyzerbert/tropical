<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Payment;

use PDF;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        config(['site.page' => 'customer']);
        $mod = new Customer();
        $data = $mod->orderBy('created_at', 'desc')->get();
        return view('customer.index', compact('data'));
    }

    public function create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        Customer::create([
            'name' => $request->get('name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'note' => $request->get('note'),
        ]);
        return response()->json('success');
    }

    public function ajax_create(Request $request){
        $request->validate([
            'name'=>'required|string',
            'company'=>'required|string',
        ]);
        
        $supplier = Customer::create([
            'name' => $request->get('name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'note' => $request->get('note'),
        ]);

        return response()->json($supplier);
    }

    public function edit(Request $request){
        $request->validate([
            'name'=>'required',
            'company'=>'required|string',
        ]);
        $item = Customer::find($request->get("id"));
        $item->name = $request->get("name");
        $item->company = $request->get("company");
        $item->email = $request->get("email");
        $item->phone_number = $request->get("phone_number");
        $item->address = $request->get("address");
        $item->city = $request->get("city");
        $item->note = $request->get("note");
        $item->save();
        return response()->json('success');
    }

    public function delete($id){
        $item = Customer::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }

    public function report($id)
    {
        $customer = Customer::find($id);
        $pdf = PDF::loadView('customer.report', compact('customer'))->setPaper('a4', 'landscape');
  
        return $pdf->download('customer_report_'.$customer->name.'.pdf');    
        // return view('customer.report', compact('customer'));
    }

    public function sales(Request $request, $id) {
        config(['site.page' => 'customer']);
        $customer = Customer::find($id);

        $mod = $customer->sales();
        $keyword = $week_c = $week_d = '';
        $pagesize = 15;
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $mod = $mod->where(function($query) use($keyword){
                return $query->where('reference_no', 'LIKE', "%$keyword%")
                        ->orWhere('issue_date', 'LIKE', "%$keyword%")
                        ->orWhere('due_date', 'LIKE', "%$keyword%")
                        ->orWhere('delivery_date', 'LIKE', "%$keyword%");
            });
        }
        if ($request->get('week_c') != ""){
            $week_c = $request->get('week_c');
            if(strpos($week_c, ",") !== false) {
                $week_c_array = explode(',', $week_c);
                $proforma_array = SaleProforma::whereIn('week_c', $week_c_array)->pluck('id');
            } else {
                $proforma_array = SaleProforma::where('week_c', $week_c)->pluck('id');
            }            
            $mod = $mod->whereIn('proforma_id', $proforma_array);
        }
        if ($request->get('week_d') != ""){
            $week_d = $request->get('week_d');
            if(strpos($week_d, ",") !== false) {
                $week_d_array = explode(',', $week_d);
                $proforma_array = SaleProforma::whereIn('week_d', $week_d_array)->pluck('id');
            } else {
                $proforma_array = SaleProforma::where('week_d', $week_d)->pluck('id');
            } 
            $mod = $mod->whereIn('proforma_id', $proforma_array);
        }
        
        
        $data = $mod->orderBy('created_at', 'desc')->paginate($pagesize);
        return view('customer.sale', compact('data', 'customer', 'keyword', 'week_c', 'week_d', 'pagesize'));
    }

    public function payments(Request $request, $id) {
        config(['site.page' => 'customer']);
        $customer = Customer::find($id);
        $mod = new Payment();
        $invoices_array = $customer->sales()->pluck('id');
        
        $reference_no = $period = '';
        $pagesize = 15;

        $mod = $mod->whereIn('invoice_id', $invoices_array);

        if ($request->get('reference_no') != ""){
            $reference_no = $request->get('reference_no');
            $mod = $mod->where('reference_no', 'LIKE', "%$reference_no%");
        }

        if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
            $mod = $mod->whereBetween('timestamp', [$from, $to]);
        }
        if($request->pagesize != '') {
            $pagesize = $request->pagesize;
        }
        $data = $mod->orderBy('timestamp', 'desc')->paginate($pagesize);
        return view('customer.payment', compact('data', 'customer', 'reference_no', 'period', 'pagesize'));
    }
}
