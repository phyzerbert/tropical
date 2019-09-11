@extends('layouts.master')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.shipment_detail')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.shipment_detail')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content content-boxed">
        <div class="block block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title p-3" style="font-size:35px">SHIPMENT : {{$shipment->reference_no}}</h3>
            </div>
            <div class="block-content">
                <div class="p-sm-4 p-xl-6">
                    <div class="row mb-5">
                        <div class="col-md-12">                            
                            <h4>{{__('page.date')}} : @if($shipment->proforma){{ date('d/m/Y', strtotime($shipment->proforma->date)) }}@else <span class="text-muted">Deleted Pro-forma</span> @endif</h4>
                            <h4>{{__('page.week_c')}} : {{$shipment->week_c}}</h4>
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
                                @foreach ($shipment->items as $item)
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
                                        {{ number_format($shipment->total_to_pay, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="clearfix">
                        <a href="{{route('shipment.index')}}" class="btn btn-primary float-right"><i class="far fa-file-alt"></i> {{__('page.shipment')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
