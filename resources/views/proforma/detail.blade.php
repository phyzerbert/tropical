@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('master/js/plugins/imageviewer/css/jquery.verySimpleImageViewer.css')}}">
    <style>
        #image_preview {
            max-width: 600px;
            height: 600px;
        }
        .image_viewer_inner_container {
            width: 100% !important;
        }
    </style>
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
                <h3 class="block-title p-3" style="font-size:35px">PRO-FORMA INVOICE NR : {{$invoice->reference_no}}</h3>
            </div>
            <div class="block-content">
                <div class="p-sm-4 p-xl-6">
                    <div class="row mb-5">
                        <div class="col-md-5">
                            <h4>{{__('page.supplier')}} : @isset($invoice->supplier->company){{ $invoice->supplier->company }}@endif</h4>
                            <h4>{{__('page.date')}} : {{ date('d/m/Y', strtotime($invoice->date)) }}</h4>
                            <h4>{{__('page.due_date')}} : {{ date('d/m/Y', strtotime($invoice->due_date)) }}</h4>
                            <h4>{{__('page.customers_vat')}} : {{$invoice->customers_vat}}</h4>
                            <h4>{{__('page.concerning_week')}} : {{$invoice->concerning_week}}</h4>
                            <h4>{{__('page.vessel')}} : {{$invoice->vessel}}</h4>
                            <h4>{{__('page.port_of_charge')}} : {{$invoice->port_of_charge}}</h4>
                            <h4>{{__('page.port_of_discharge')}} : {{$invoice->port_of_discharge}}</h4>
                            <h4>{{__('page.origin')}} : {{$invoice->origin}}</h4>
                            <h4>{{__('page.week_c')}} : {{$invoice->week_c}}</h4>
                            <h4>{{__('page.week_d')}} : {{$invoice->week_d}}</h4>
                        </div>
                        <div class="col-md-7">
                            <div class="block block-content">
                                <img src="@if($invoice->image){{asset($invoice->image)}}@else{{asset('images/no-image.jpg')}}@endif"  class="attachment" data-value="@if($invoice->image){{asset($invoice->image)}}@else{{asset('images/no-image.jpg')}}@endif" width="100%" alt="">                                
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered">
                            <thead class="bg-body">
                                <tr>
                                    <th class="text-center" style="width: 60px;"></th>
                                    <th>{{__('page.product_name_code')}}</th>
                                    <th class="text-right" style="width: 90px;">{{__('page.quantity')}}</th>
                                    <th class="text-right" style="width: 120px;">{{__('page.price')}}</th>
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
                                            <p class="font-w600 mb-1">{{$item->product->name}} ( {{$item->product->code}} )</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-primary">{{number_format($item->quantity)}}</span>
                                        </td>
                                        <td class="text-right">{{$item->price}}</td>
                                        <td class="text-right">{{number_format($item->total_amount, 2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">{{__('page.total')}} : </th>
                                    <th class="total_quantity">{{ $footer_quantity }}</th>
                                    <th></th>
                                    <th colspan="2" class="total">{{ number_format($footer_amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total To Pay</th>
                                    <th colspan="2">
                                        {{ number_format($invoice->total_to_pay, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @php
                                $paid = $invoice->payments->sum('amount');
                            @endphp
                            <h4 class="text-right">
                                {{__('page.invoice')}} : <span class="text-primary">{{number_format($invoice->total_to_pay, 2)}}</span> 
                                {{__('page.payment')}} : <span class="text-primary">{{number_format($paid, 2)}}</span>
                                {{__('page.balance')}} : <span class="text-primary">{{number_format($invoice->total_to_pay - $paid, 2)}}</span>
                            </h4>
                        </div>
                        <div class="col-12">
                            <div class="block block-rounded block-bordered">
                                <div class="block-content">
                                    <p>{{$invoice->note}}</p>
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-colored">
                                        <tr class="bg-blue">
                                            <th style="width:40px;">#</th>
                                            <th>{{__('page.date')}}</th>
                                            <th>{{__('page.reference_no')}}</th>
                                            <th>{{__('page.amount')}}</th> 
                                            <th>{{__('page.note')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                
                                        @foreach ($invoice->payments as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="date">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                                <td class="reference_no">{{$item->reference_no}}</td>
                                                <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount)}}</td>
                                                <td class="" data-path="{{$item->attachment}}">
                                                    <span class="tx-info note">{{$item->note}}</span>&nbsp;
                                                    @if($item->attachment != "")
                                                        <a href="#" class="attachment" data-value="{{asset($item->attachment)}}"><i class="fa fa-paperclip"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                    <div class="clearfix">
                        <a href="{{route('proforma.index')}}" class="btn btn-primary float-right"><i class="far fa-file-alt"></i> {{__('page.proforma')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachModal">
        <div class="modal-dialog" style="margin-top:17vh">
            <div class="modal-content">
                <div id="image_preview"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('master/js/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(".attachment").click(function(e){
                let path = $(this).data('value');
                $("#image_preview").html('')
                $("#image_preview").verySimpleImageViewer({
                    imageSource: path,
                    frame: ['100%', '100%'],
                    maxZoom: '900%',
                    zoomFactor: '10%',
                    mouse: true,
                    keyboard: true,
                    toolbar: true,
                });
                $("#attachModal").modal();
            });
        })
    </script>
@endsection