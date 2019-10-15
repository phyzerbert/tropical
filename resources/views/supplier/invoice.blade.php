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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.supplier_invoices')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item" aria-current="page">{{__('page.supplier')}}</li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.invoices')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" class="col-md-12 form-inline px-0" method="post" id="searchForm">
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
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2 mt-md-0" name="keyword" id="search_keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <input type="text" class="form-control form-control-sm col-md-1 mt-2 mt-md-0 ml-md-2" name="week_c" id="search_week_c" value="{{$week_c}}" placeholder="{{__('page.week_c')}}">
                    <input type="text" class="form-control form-control-sm col-md-1 mt-2 mt-md-0 ml-md-2" name="week_d" id="search_week_d" value="{{$week_d}}" placeholder="{{__('page.week_d')}}">
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2 mt-md-0"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <button type="button" class="btn btn-danger btn-sm mt-2 mt-md-0 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</button>
                    <a href="{{route('supplier.invoices', $supplier->id)}}" class="btn btn-info btn-sm mt-2 mt-md-0 ml-auto"><i class="fa fa-credit-card"></i> {{__('page.invoices')}}</a>
                    <a href="{{route('supplier.payments', $supplier->id)}}" class="btn btn-outline-info btn-sm mt-2 mt-md-0 ml-2"><i class="far fa-money-bill-alt"></i> {{__('page.payments')}}</a>
                </form>                
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-7">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>{{__('page.reference_no')}}</th>
                                <th>{{__('page.supplier')}}</th>
                                <th>{{__('page.issue_date')}}</th>
                                <th>{{__('page.due_date')}}</th>
                                <th>{{__('page.concerning_week')}}</th>
                                <th>{{__('page.total_to_pay')}}</th>
                                <th>{{__('page.paid')}}</th>
                                <th>{{__('page.balance')}}</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @php
                                $footer_total_to_pay = $footer_paid = $footer_balance = 0;
                            @endphp                               
                            @foreach ($data as $item)
                                @php
                                    $paid = $item->payments()->sum('amount');
                                    $balance = $item->total_to_pay - $paid;
                                    $footer_total_to_pay += $item->total_to_pay;
                                    $footer_balance += $balance;
                                    $footer_paid += $paid;
                                @endphp
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="supplier">@isset($item->supplier->company){{$item->supplier->company}}@endisset</td>
                                    <td class="issue_date">{{ date('d/m/Y', strtotime($item->issue_date)) }}</td>
                                    <td class="due_date">{{ date('d/m/Y', strtotime($item->due_date)) }}</td>
                                    <td class="concerning_week">{{$item->concerning_week}}</td>
                                    <td class="total_to_pay">{{number_format($item->total_to_pay, 2)}}</td>
                                    <td class="paid">{{number_format($paid, 2)}}</td>
                                    <td class="balance" data-value="{{$balance}}">{{number_format($balance, 2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">{{__('page.total')}}</th>
                                <th>{{number_format($footer_total_to_pay, 2)}}</th>
                                <th>{{number_format($footer_paid, 2)}}</th>
                                <th>{{number_format($footer_balance, 2)}}</th>
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
                                'week_c' => $week_c,
                                'week_d' => $week_d,
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
                    <input type="hidden" name="type" value="invoice">
                    <input type="hidden" class="invoice_id" name="invoice_id" />
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
                            <input class="form-control amount" type="text" name="amount" required placeholder="{{__('page.amount')}}">
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
                $("#payment_form .invoice_id").val(id);
                $("#payment_form .amount").val(balance);
                $("#paymentModal").modal();
            });

            
            $("#btn-reset").click(function(){
                $("#search_keyword").val('');
                $("#search_week_c").val('');
                $("#search_week_d").val('');
            });

            $("#pagesize").change(function(){
                $("#searchForm").submit();
            });
        })
    </script>
@endsection
