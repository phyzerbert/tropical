@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('master/js/plugins/select2/css/select2.min.css')}}">  
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
                <h3 class="block-title py-3" style="font-size:35px">INVOICE : {{$invoice->reference_no}}</h3>
            </div>
            <div class="block-content">
                <div class="p-sm-4 p-xl-6">
                    <div class="row mb-5">
                        <div class="col-md-5">
                            <h4>{{__('page.issue_date')}} : <ins>{{$invoice->issue_date}}</ins></h4>
                            <h4>{{__('page.due_date')}} : <ins>{{$invoice->due_date}}</ins></h4>
                            <h4>{{__('page.customers_vat')}} : <ins>{{$invoice->customers_vat}}</ins></h4>
                            <h4>{{__('page.delivery_date')}} : <ins>{{$invoice->delivery_date}}</ins></h4>
                            <h4>{{__('page.concerning_week')}} : <ins>{{$invoice->concerning_week}}</ins></h4>
                            <h4>{{__('page.shipment')}} : <ins>{{$invoice->shipment}}</ins></h4>
                            <h4>{{__('page.vessel')}} : <ins>{{$invoice->vessel}}</ins></h4>
                            <h4>{{__('page.port_of_discharge')}} : <ins>{{$invoice->port_of_discharge}}</ins></h4>
                            <h4>{{__('page.origin')}} : <ins>{{$invoice->origin}}</ins></h4>
                        </div>
                        <div class="col-md-7">
                            <div class="block block-content text-center">
                                @if($invoice->image)
                                    @php
                                        $path_info = pathinfo($invoice->image);
                                        $attach_ext = $path_info['extension']; 
                                    @endphp
                                    @if($attach_ext == 'pdf')
                                        <img src="{{asset('images/pdf.png')}}" class="ez_attach" width="50%" href="{{asset($invoice->image)}}" width="100%" alt="">                                
                                    @else
                                        <img src="{{asset($invoice->image)}}" class="ez_attach" width="100%" alt="">                                
                                    @endif
                                @else
                                    <img src="{{asset('images/no-image.jpg')}}" class="ez_attach" width="100%" alt="">
                                @endif
                            </div>
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
                                            <div class="text-muted">{{$item->product->name}}</div>
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
                                    <th colspan="2" class="text-right">{{__('page.total')}} : </th>
                                    <th class="total_quantity text-center">
                                        <span class="badge badge-pill badge-success">{{number_format($footer_quantity)}}</span>
                                    </th>
                                    <th colspan="3" class="text-right">Total Excluding VAT</th>
                                    <th colspan="2" class="total_excluding_vat">{{ number_format($footer_amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">V.A.T</th>
                                    <th colspan="2">{{ $invoice->vat_amount }}</th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">Total Including VAT</th>
                                    <th colspan="2">{{ number_format($invoice->total_to_pay, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">Total To Pay</th>
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
                                        @forelse ($invoice->payments as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="date">{{date('Y-m-d', strtotime($item->timestamp))}}</td>
                                                <td class="reference_no">{{$item->reference_no}}</td>
                                                <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount, 2)}}</td>
                                                <td class="" data-path="{{$item->attachment}}">
                                                    <span class="tx-info note">{{$item->note}}</span>&nbsp;&nbsp;
                                                    @if($item->attachment != "")
                                                        @php
                                                            $path_info = pathinfo($item->attachment);
                                                            $attach_ext = $path_info['extension'];
                                                        @endphp
                                                        @if($attach_ext == 'pdf')
                                                            <img class="ez_attach1 text-primary" src="{{asset('images/attachment.png')}}" height="25" href="{{asset($item->attachment)}}" />
                                                        @else
                                                            <img class="ez_attach1 text-primary" src="{{asset($item->attachment)}}" height="30" />
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>                                        
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center font-weight-bold">{{__('page.no_payment')}}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                    <div class="clearfix">
                        <a href="{{route('invoice.index')}}" class="btn btn-primary float-right"><i class="far fa-file-alt"></i> {{__('page.invoices')}}</a>
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
<script src="{{asset('master/js/plugins/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('master/js/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
<script src="{{asset('master/js/plugins/ezview/EZView.js')}}"></script>
<script>
        $(document).ready(function(){
            $(".attachment").click(function(e){
                e.preventDefault();
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
            $(".ez_attach").EZView();
            $(".ez_attach1").EZView();
        })
    </script>
@endsection




