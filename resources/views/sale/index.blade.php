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
                <form action="" method="POST" class="col-md-12 form-inline" id="searchForm">
                    @csrf
                    <input type="hidden" name="sort_by_date" value="{{$sort_by_date}}" id="search_sort_date" />
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
                                <th style="width:40px;">#</th>
                                <th>
                                    {{__('page.date')}}
                                    <span class="sort-date float-right">
                                        @if($sort_by_date == 'desc')
                                            <i class="fa fa-angle-up"></i>
                                        @elseif($sort_by_date == 'asc')
                                            <i class="fa fa-angle-down"></i>
                                        @endif
                                    </span>
                                </th>
                                <th>{{__('page.reference_no')}}</th>
                                <th>{{__('page.customer')}}</th>
                                <th>{{__('page.sale_status')}}</th>
                                <th>{{__('page.grand_total')}}</th>
                                <th>{{__('page.paid')}}</th>
                                <th>{{__('page.balance')}}</th>
                                <th>{{__('page.payment_status')}}</th>
                                <th>{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $footer_grand_total = $footer_paid = 0;
                            @endphp
                            @foreach ($data as $item)
                                @php
                                    $paid = $item->payments()->sum('amount');
                                    $grand_total = $item->grand_total;
                                    $footer_grand_total += $grand_total;
                                    $footer_paid += $paid;
                                @endphp
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="timestamp">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="customer" data-id="{{$item->customer_id}}">{{$item->customer->company}}</td>
                                    <td class="status">
                                        @if ($item->status == 1)
                                            <span class="badge badge-success">{{__('page.received')}}</span>
                                        @elseif($item->status == 0)
                                            <span class="badge badge-primary">{{__('page.pending')}}</span>
                                        @endif
                                    </td>
                                    <td class="grand_total"> {{number_format($grand_total, 2)}} </td>
                                    <td class="paid"> {{ number_format($paid, 2) }} </td>
                                    <td class="balance" data-value="{{$grand_total - $paid}}"> {{number_format($grand_total - $paid, 2)}} </td>
                                    <td>
                                        @if ($paid == 0)
                                            <span class="badge badge-danger">{{__('page.pending')}}</span>
                                        @elseif($paid < $grand_total)
                                            <span class="badge badge-primary">{{__('page.partial')}}</span>
                                        @else
                                            <span class="badge badge-success">{{__('page.paid')}}</span>
                                        @endif
                                    </td>
                                    <td class="py-2" align="center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-paper-plane"></i> {{__('page.action')}}</button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item px-3" href="{{route('sale.detail', $item->id)}}"><i class="align-middle" data-feather="eye"></i> {{__('page.details')}}</a>
                                                {{-- <a class="dropdown-item px-3" href="{{route('sale.report', $item->id)}}"><i class="align-middle mr-2 far fa-file-pdf"></i> {{__('page.report')}}</a> --}}
                                                <a class="dropdown-item px-3" href="{{route('payment.index', ['sale', $item->id])}}"><i class="align-middle" data-feather="dollar-sign"></i> {{__('page.payment_list')}}</a>
                                                <a class="dropdown-item px-3 btn-add-payment" href="#" data-id="{{$item->id}}"><i class="align-middle" data-feather="credit-card"></i> {{__('page.add_payment')}}</a>
                                                <a class="dropdown-item px-3" href="{{route('sale.edit', $item->id)}}"><i class="align-middle" data-feather="edit"></i> {{__('page.edit')}}</a>
                                                <a class="dropdown-item px-3" href="{{route('sale.delete', $item->id)}}"><i class="align-middle" data-feather="trash-2"></i> {{__('page.delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">{{__('page.total')}}</th>
                                <th>{{number_format($footer_grand_total, 2)}}</th>
                                <th>{{number_format($footer_paid, 2)}}</th>
                                <th>{{number_format($footer_grand_total - $footer_paid, 2)}}</th>
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
                                'customer_id' => $customer_id,
                                'reference_no' => $reference_no,
                                'period' => $period,
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
                            <input class="form-control amount" type="text" name="amount" required placeholder="{{__('page.amount')}}">
                        </div>                                               
                        <div class="form-group">
                            <label class="control-label">{{__('page.attachment')}}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="attachment" accept="image/*">
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
            })
        })
    </script>
@endsection
