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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> PRO-FORMA INVOICE NR</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">PRO-FORMA INVOICE NR</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" class="col-md-12 form-inline">
                    @csrf
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2" id="search_week_c" name="week_c" value="{{$week_c}}" placeholder="{{__('page.week_c')}}">
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2 ml-md-2" id="search_week_d" name="week_d" value="{{$week_d}}" placeholder="{{__('page.week_d')}}">
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2 ml-md-2" id="search_week_keyword" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <a href="javascript:;" class="btn btn-danger btn-sm mt-2 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</a>
                    <a href="{{route('proforma.create')}}" class="btn btn-success float-right btn-sm mt-2 ml-auto" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
                </form>                
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-8">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>PRO-FORMA INVOICE NR</th>
                                <th>{{__('page.supplier')}}</th>
                                <th>{{__('page.date')}}</th>
                                <th>{{__('page.vessel')}}</th>
                                <th>{{__('page.port_of_charge')}}</th>
                                <th>{{__('page.port_of_discharge')}}</th>
                                <th>{{__('page.status')}}</th>
                                <th>{{__('page.total_to_pay')}}</th>
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
                                    <td class="supplier">@isset($item->supplier->company){{$item->supplier->company}}@endisset</td>
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
                                                <a class="dropdown-item" href="{{route('proforma.detail', $item->id)}}">{{__('page.detail')}}</a>
                                                <a class="dropdown-item" href="{{route('proforma.container', $item->id)}}">{{__('page.container')}}</a>
                                                @if (!$item->is_submitted)                                                    
                                                    <a class="dropdown-item" href="{{route('proforma.submit', $item->id)}}" data-id="{{$item->id}}">{{__('page.submit')}}</a>
                                                @endif
                                                <a class="dropdown-item btn-add-payment" data-id="{{$item->id}}" href="javascript:void(0)">{{__('page.add_payment')}}</a>
                                                <a class="dropdown-item" href="{{route('payment.index', ['proforma', $item->id])}}">{{__('page.payment_list')}}</a>
                                                <a class="dropdown-item" href="{{route('proforma.edit', $item->id)}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('proforma.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
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
                            {!! $data->appends(['keyword' => $keyword])->links() !!}
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
                    <input type="hidden" name="type" value="proforma">
                    <input type="hidden" class="proforma_id" name="proforma_id" />
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
                $("#payment_form .proforma_id").val(id);
                $("#payment_form .amount").val(balance);
                $("#paymentModal").modal();
            });

            $("#btn-reset").click(function(){
                $("#search_week_c").val('');
                $("#search_week_d").val('');
                $("#search_keyword").val('');
            })
        })
    </script>
@endsection
