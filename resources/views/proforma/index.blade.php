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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.invoice_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.invoice_management')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" class="form-inline mr-auto">
                    @csrf
                    <input type="text" class="form-control form-control-sm" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </form>                
                <a href="{{route('proforma.create')}}" class="btn btn-success btn-sm" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-hover">
                    <thead class="thead-colored thead-primary">
                        <tr class="bg-blue">
                            <th style="width:50px;">#</th>
                            <th>{{__('page.reference_no')}}</th>
                            <th>{{__('page.supplier')}}</th>
                            <th>{{__('page.date')}}</th>
                            <th>{{__('page.due_date')}}</th>
                            <th>{{__('page.total_to_pay')}}</th>
                            <th style="width:120px;">{{__('page.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>                              
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                <td class="reference_no">{{$item->reference_no}}</td>
                                <td class="supplier">@isset($item->supplier->company){{$item->supplier->company}}@endisset</td>
                                <td class="date">{{$item->date}}</td>
                                <td class="due_date">{{$item->due_date}}</td>
                                <td class="total_to_pay">{{number_format($item->total_to_pay)}}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                            <a class="dropdown-item" href="{{route('proforma.detail', $item->id)}}">{{__('page.detail')}}</a>
                                            <a class="dropdown-item" href="{{route('proforma.receive', $item->id)}}" data-id="{{$item->id}}">{{__('page.receive')}}</a>
                                            <a class="dropdown-item" href="{{route('proforma.edit', $item->id)}}">{{__('page.edit')}}</a>
                                            <a class="dropdown-item" href="{{route('invoice.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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
                    <input type="hidden" class="paymentable_id" name="paymentable_id" />
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
                                <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
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
                $("#payment_form .paymentable_id").val(id);
                $("#payment_form .amount").val(balance);
                $("#paymentModal").modal();
            });
        })
    </script>
@endsection
