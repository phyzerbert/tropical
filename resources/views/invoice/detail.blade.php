@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('master/js/plugins/select2/css/select2.min.css')}}">
    <script src="{{asset('master/js/plugins/vuejs/vue.js')}}"></script>
    <script src="{{asset('master/js/plugins/vuejs/axios.js')}}"></script>
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.invoice_detail')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.invoice_detail')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content content-boxed">
        <div class="block block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title py-3" style="font-size:35px">INVOICE : {{$invoice->reference_no}}</h3>
            </div>
            <div class="block-content">
                <div class="p-sm-4 p-xl-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h4>{{__('page.issue_date')}} : {{$invoice->issue_date}}</h4>
                            <h4>{{__('page.due_date')}} : {{$invoice->due_date}}</h4>
                            <h4>{{__('page.customers_vat')}} : {{$invoice->customers_vat}}</h4>
                            <h4>{{__('page.delivery_date')}} : {{$invoice->delivery_date}}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4>{{__('page.concerning_week')}} : {{$invoice->concerning_week}}</h4>
                            <h4>{{__('page.shipment')}} : {{$invoice->shipment}}</h4>
                            <h4>{{__('page.vessel')}} : {{$invoice->vessel}}</h4>
                            <h4>{{__('page.port_of_discharge')}} : {{$invoice->port_of_discharge}}</h4>
                            <h4>{{__('page.origin')}} : {{$invoice->origin}}</h4>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered">
                            <thead class="bg-body">
                                <tr>
                                    <th class="text-center" style="width: 60px;"></th>
                                    <th>{{__('page.product')}}</th>
                                    <th class="text-right" style="width: 90px;">{{__('page.units_boxes')}}</th>
                                    <th class="text-right" style="width: 120px;">{{__('page.price')}}</th>
                                    <th class="text-right">{{__('page.amount')}}</th>
                                    <th class="text-right">{{__('page.surcharge_reduction')}}</th>
                                    <th class="text-right" style="width: 120px;">{{__('page.total_amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $footer_quantity = $footer_amount = 0;
                                @endphp
                                @foreach ($invoice->items as $item)
                                    @php
                                        $footer_quantity += $item->quantity;
                                        $footer_amount += $item->total_amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$loop->index + 1}}</td>
                                        <td>
                                            <p class="font-w600 mb-1">{{$item->product->code}}</p>
                                            <div class="text-muted">{{$item->product->description}}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-primary">{{number_format($item->quantity)}}</span>
                                        </td>
                                        <td class="text-right">{{$item->price}}</td>
                                        <td class="text-right">{{number_format($item->amount, 2)}}</td>
                                        <td class="text-right">{{$item->surcharge_reduction}}</td>
                                        <td class="text-right">{{number_format($item->total_amount, 2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right">{{__('page.total')}} : </td>
                                    <td class="total_quantity text-center">
                                        <span class="badge badge-pill badge-success">{{number_format($footer_quantity)}}</span>
                                    </td>
                                    <td colspan="3" align="right">Total Excluding VAT</td>
                                    <td colspan="2" class="total_excluding_vat">{{ number_format($footer_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="right">V.A.T</td>
                                    <td colspan="2">{{ $invoice->vat_amount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="right">Total Including VAT</td>
                                    <td colspan="2">{{ number_format($invoice->total_to_pay, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" align="right">Total To Pay</td>
                                    <td colspan="2">
                                        {{ number_format($invoice->total_to_pay, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="clearfix">
                        <a href="{{route('invoice.index')}}" class="btn btn-primary float-right"><i class="far fa-file-alt"></i> {{__('page.invoices')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')

@endsection




