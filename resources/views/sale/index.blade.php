@extends('layouts.master')
@section('style')
    <link href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
    {{-- <link href="{{asset('master/js/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet"> --}}
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.sales_list')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.sales_list')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" method="POST" class="col-md-12 form-inline px-0" id="searchForm">
                    @csrf
                    <input type="hidden" name="sort_by_date" value="{{$sort_by_date}}" id="search_sort_date" />
                    <label for="pagesize" class="control-label mt-2">{{__('page.show')}} :</label>
                    <select class="form-control form-control-sm mx-md-2 mt-2" name="pagesize" id="pagesize">
                        <option value="15" @if($pagesize == '15') selected @endif>15</option>
                        <option value="50" @if($pagesize == '50') selected @endif>50</option>
                        <option value="100" @if($pagesize == '100') selected @endif>100</option>
                        <option value="200" @if($pagesize == '200') selected @endif>200</option>
                        <option value="500" @if($pagesize == '500') selected @endif>500</option>
                        <option value="" @if($pagesize == '100000') selected @endif>All</option>
                    </select>
                    <input type="text" class="form-control form-control-sm col-md-3 mt-2" name="keyword" id="search_keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <button type="button" class="btn btn-danger btn-sm mt-2 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</button>
                    <a href="{{route('sale.create')}}" class="btn btn-success btn-sm mt-2 ml-auto" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
                </form>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-7">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>PRO-FORMA</th>
                                <th>{{__('page.customer')}}</th>
                                <th>{{__('page.date')}}</th>
                                <th>{{__('page.vessel')}}</th>
                                <th>{{__('page.port_of_charge')}}</th>
                                <th>{{__('page.port_of_discharge')}}</th>
                                <th>{{__('page.status')}}</th>
                                <th>{{__('page.grand_total')}}</th>
                                <th>{{__('page.paid')}}</th>
                                <th>{{__('page.balance')}}</th>
                                <th style="width:120px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $footer_total_to_pay = $footer_paid = $footer_balance = 0;
                            @endphp                              
                            @foreach ($data as $item)
                                <tr>
                                    @php
                                        $paid = $item->payments()->sum('amount');
                                        $balance = $item->total_to_pay - $paid;
                                        $footer_total_to_pay += $item->total_to_pay;
                                        $footer_balance += $balance;
                                        $footer_paid += $paid;
                                    @endphp
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="customer">@isset($item->customer->company){{$item->customer->company}}@endisset</td>
                                    <td class="date">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                    <td class="vessel">{{ $item->vessel }}</td>
                                    <td class="port_of_charge">{{ $item->port_of_charge }}</td>
                                    <td class="port_of_discharge">{{ $item->port_of_discharge }}</td>
                                    <td class="status">
                                        @if($item->is_submitted == 1)
                                            <span class="badge badge-success">{{__('page.embarked')}}</span>
                                        @else
                                            <span class="badge badge-warning">{{__('page.pending')}}</span>
                                        @endif
                                    </td>
                                    <td class="total_to_pay">{{number_format($item->total_to_pay, 2)}}</td>
                                    <td class="paid">{{number_format($paid, 2)}}</td>
                                    <td class="balance" data-value="{{$balance}}">{{number_format($balance, 2)}}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                <a class="dropdown-item" href="{{route('sale.detail', $item->id)}}">{{__('page.detail')}}</a>
                                                <a class="dropdown-item" href="{{route('sale.report', $item->id)}}">{{__('page.report')}}</a>
                                                <a class="dropdown-item" href="{{route('sale.email', $item->id)}}">{{__('page.email')}}</a>
                                                <a class="dropdown-item btn-add-payment" data-id="{{$item->id}}" href="javascript:void(0)">{{__('page.add_payment')}}</a>
                                                <a class="dropdown-item" href="{{route('payment.index', ['sale', $item->id])}}">{{__('page.payment_list')}}</a>
                                                <a class="dropdown-item" href="{{route('sale.edit', $item->id)}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('sale.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8">{{__('page.total')}}</th>
                                <th>{{number_format($footer_total_to_pay, 2)}}</th>
                                <th>{{number_format($footer_paid, 2)}}</th>
                                <th>{{number_format($footer_balance, 2)}}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>                
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {!! $data->appends([
                                'keyword' => $keyword,  
                                'pagesize' => $pagesize,                              
                            ])->links() !!}
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_payment')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('payment.create')}}" id="payment_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="sale">
                    <input type="hidden" class="sale_id" name="sale_id" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.date')}}</label>
                            <input class="form-control date" type="text" name="date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}">
                        </div>                        
                        <div class="form-group">
                            <label class="control-label">{{__('page.reference_no')}}</label>
                            <input class="form-control reference_no" type="text" name="reference_no" required placeholder="{{__('page.reference_no')}}">
                        </div>                                                
                        <div class="form-group">
                            <label class="control-label">{{__('page.amount')}}</label>
                            <input class="form-control amount" type="number" name="amount" required placeholder="{{__('page.amount')}}">
                        </div>                                               
                        <div class="form-group">
                            <label class="control-label">{{__('page.attachment')}}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="attachment" accept="image/*,application/pdf">
                                <label class="custom-file-label" for="example-file-input-custom">{{__('page.choose_file')}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.note')}}</label>
                            <textarea class="form-control note" type="text" name="note" placeholder="{{__('page.note')}}"></textarea>
                        </div> 
                    </div>    
                    <div class="modal-footer">
                        <button type="submit" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('master/js/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>
    {{-- <script src="{{asset('master/js/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script> --}}
    <script>
        $(document).ready(function(){
            $("#payment_form input.date").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });
            
            $(".btn-add-payment").click(function(){
                let id = $(this).data('id');
                let balance = $(this).parents('tr').find('.balance').data('value');
                $("#payment_form .sale_id").val(id);
                $("#payment_form .amount").val(balance);
                $("#paymentModal").modal();
            });

            $(".sort-date").click(function(){
                let status = $("#search_sort_date").val();
                if (status == 'asc') {
                    $("#search_sort_date").val('desc');
                } else {
                    $("#search_sort_date").val('asc');
                }
                $("#searchForm").submit();
            })
            
            $("#btn-reset").click(function(){
                $("#search_keyword").val('');
            });

            $("#pagesize").change(function(){
                $("#searchForm").submit();
            });
        })
    </script>
@endsection
