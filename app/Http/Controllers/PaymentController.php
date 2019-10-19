<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Sale;
use App\Models\SaleProforma;
use App\Models\Transaction;
use PDF;

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
        }else if($type == 'sale_proforma'){
            config(['site.page' => 'sale_proforma']);
            $paymentable = SaleProforma::find($id);
        } 
        $data = $paymentable->payments;
        return view('payment.index', compact('data', 'type'));
    }

    public function create(Request $request){
        $request->validate([
            'date'=>'required|string',
            'reference_no'=>'required|string',
        ]);        
        $transaction_type = 1;
        $category_id = 1;
        $supplier_customer = '';
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
            $transaction_type = 1;
            $supplier_customer = $invoice->supplier->company;
        }else if($request->type == 'proforma'){
            $item->proforma_id = $request->proforma_id;
            $proforma = Proforma::find($request->proforma_id);
            if($proforma->invoice) {
                $item->invoice_id = $proforma->invoice->id;
            }
            $transaction_type = 1;
            $supplier_customer = $proforma->supplier->company;
        }else if($request->type == 'sale'){
            $item->sale_id = $request->sale_id;
            $sale = Sale::find($request->sale_id);
            if($sale->sale_proforma_id) {
                $item->sale_proforma_id = $sale->sale_proforma_id;
            }
            $transaction_type = 2;
            $supplier_customer = $sale->customer->company;
        }else if($request->type == 'sale_proforma'){
            $item->sale_proforma_id = $request->sale_proforma_id;
            $proforma = SaleProforma::find($request->sale_proforma_id);
            if($proforma->sale) {
                $item->sale_id = $proforma->sale->id;
            }
            $transaction_type = 2;
            $supplier_customer = $proforma->customer->company;
        }
        $item->note = $request->get('note');
        if($request->has("attachment")){
            $picture = request()->file('attachment');
            $imageName = "payment_".time().'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('images/uploaded/payment_images/'), $imageName);
            $item->attachment = 'images/uploaded/payment_images/'.$imageName;
        }
        $item->save();
        Transaction::create([
            'reference_no' => $item->reference_no,
            'timestamp' => $item->timestamp,
            'amount' => $item->amount,
            'category_id' => $transaction_type,
            'payment_id' => $item->id,
            'supplier_customer' => $supplier_customer,
            'type' => $transaction_type,
            'note' => $item->note,
            'attachment' => $item->attachment,
        ]);
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

    public function report($id) {
        $payment = Payment::find($id);
        $pdf = PDF::loadView('payment.report', compact('payment'));
  
        return $pdf->download('payment_report_'.$payment->reference_no.'.pdf');    
        // return view('payment.report', compact('payment'));
    }


    public function delete($id){
        $item = Payment::find($id);
        $item->delete();
        return back()->with("success", __('page.deleted_successfully'));
    }
}
