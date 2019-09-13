<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Sale;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $type, $id)
    {
        if($type == 'invoice'){
            config(['site.page' => 'invoice']);
            $paymentable = Invoice::find($id);
        }else if($type == 'proforma'){
            config(['site.page' => 'proforma']);
            $paymentable = Proforma::find($id);
        }else if($type == 'sale'){
            config(['site.page' => 'sale']);
            $paymentable = Sale::find($id);
        }        
        $data = $paymentable->payments;
        return view('payment.index', compact('data', 'type'));
    }

    public function create(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_no'=>'required|string',
        ]);        
        
        $item = new Payment();
        $item->timestamp = $request->get('date').":00";
        $item->reference_no = $request->get('reference_no');
        $item->amount = $request->get('amount');
        if($request->type == 'invoice'){
            $item->invoice_id = $request->invoice_id;
            $invoice = Invoice::find($request->invoice_id);
            if($invoice->proforma_id) {
                $item->proforma_id = $invoice->proforma_id;
            }
        }else if($request->type == 'proforma'){
            $item->proforma_id = $request->proforma_id;
            $proforma = Proforma::find($request->proforma_id);
            if($proforma->invoice) {
                $item->invoice_id = $proforma->invoice->id;
            }
        }else if($request->type == 'sale'){
            $item->sale_id = $request->sale_id;
        }
        $item->note = $request->get('note');
        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "payment_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/payment_images/'), $imageName);
            $item->attachment = 'images/uploaded/payment_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.added_successfully'));
    }

    public function edit(Request $request){
        $request->validate([
            'date'=>'required',
        ]);
        $data = $request->all();
        $item = Payment::find($request->get("id"));
        $item->timestamp = $request->get("date");
        $item->reference_no = $request->get("reference_no");
        $item->amount = $request->get("amount");
        $item->note = $request->get("note");
        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "payment_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/payment_images/'), $imageName);
            $item->attachment = 'images/uploaded/payment_images/'.$imageName;
        }
        $item->save();
        return back()->with('success', __('page.updated_successfully'));
    }


    public function delete($id){
        $item = Payment::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
