@extends('layouts.master')
@section('style')
    <link rel="stylesheet" id="css-main" href="{{asset('master/js/plugins/datatables/dataTables.bootstrap4.css')}}">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.shipment')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.shipment')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            {{-- <div class="block-header block-header-default">
                <form action="" class="form-inline mr-auto" id="searchForm">
                    @csrf
                    <label for="pagesize" class="control-label">{{__('page.show')}} :</label>
                    <select class="form-control form-control-sm mx-md-2" name="pagesize" id="pagesize">
                        <option value="15" @if($pagesize == '15') selected @endif>15</option>
                        <option value="50" @if($pagesize == '50') selected @endif>50</option>
                        <option value="100" @if($pagesize == '100') selected @endif>100</option>
                        <option value="200" @if($pagesize == '200') selected @endif>200</option>
                        <option value="500" @if($pagesize == '500') selected @endif>500</option>
                        <option value="" @if($pagesize == '100000') selected @endif>All</option>
                    </select>
                    <input type="text" class="form-control form-control-sm mt-2 mt-md-0" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-2 mt-2 mt-md-0"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </form>
            </div> --}}
            <div class="block-content block-content-full">
                <div class="table-responsive pb-7">                    
                    <table class="table table-bordered table-hover" id="shipmentTable">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>{{__('page.proforma')}}</th>
                                <th>{{__('page.date')}}</th>
                                <th>{{__('page.week_c')}}</th>
                                <th>{{__('page.status')}}</th>
                                <th>{{__('page.total_amount')}}</th>
                                <th>{{__('page.paid')}}</th>
                                <th>{{__('page.balance_of_proforma')}}</th>
                                <th style="width:120px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $footer_total_to_pay = $footer_paid = $footer_balance = 0;
                            @endphp
                            @foreach ($data as $item)
                                @php
                                    $proforma_paid = $proforma_balance = 0;
                                    if($item->proforma){
                                        $proforma_total_to_pay = $item->proforma->total_to_pay;
                                        $proforma_paid = $item->proforma->payments()->sum('amount');
                                        $proforma_balance = $proforma_total_to_pay - $proforma_paid;
                                    }
                                    $footer_total_to_pay += $item->total_to_pay;
                                    $footer_paid += $proforma_paid;
                                    $footer_balance += $proforma_balance;
                                @endphp
                                <tr>
                                    {{-- <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td> --}}
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="date">@if($item->proforma){{ date('d/m/Y', strtotime($item->proforma->date)) }}@endif</td>
                                    <td class="week_c">{{ $item->week_c }}</td>
                                    <td class="status">
                                        @if($item->is_received == 1)
                                            <span class="badge badge-success">{{__('page.received')}}</span>
                                        @else
                                            <span class="badge badge-warning">{{__('page.pending')}}</span>
                                        @endif
                                    </td>
                                    <td class="total_to_pay">{{number_format($item->total_to_pay, 2)}}</td>
                                    <td class="paid">{{number_format($proforma_paid, 2)}}</td>
                                    <td class="balance_of_proforma">
                                        {{number_format($proforma_balance, 2)}}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                <a class="dropdown-item" href="{{route('shipment.detail', $item->id)}}">{{__('page.detail')}}</a>
                                                @if (!$item->is_received)                                                    
                                                    <a class="dropdown-item" href="{{route('shipment.receive', $item->id)}}" data-id="{{$item->id}}">{{__('page.receive')}}</a>
                                                @endif
                                                <a class="dropdown-item" href="{{route('shipment.edit', $item->id)}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('shipment.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th>{{number_format($footer_total_to_pay, 2)}}</th>
                                <th>{{number_format($footer_paid, 2)}}</th>
                                <th>{{number_format($footer_balance, 2)}}</th>                                
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>                
                    {{-- <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {!! $data->appends([
                                'keyword' => $keyword,
                                'pagesize' => $pagesize,
                            ])->links() !!}
                        </div>
                    </div> --}}
                </div>                
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{asset('master/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function(){  
            $("#shipmentTable").dataTable();
            // $(".btn-add-payment").click(function(){
            //     let id = $(this).data('id');
            //     let balance = $(this).parents('tr').find('.balance').data('value');
            //     $("#payment_form .proforma_id").val(id);
            //     $("#payment_form .amount").val(balance);
            //     $("#paymentModal").modal();
            // });

            $("#pagesize").change(function(){
                $("#searchForm").submit();
            });
        })
    </script>
@endsection
