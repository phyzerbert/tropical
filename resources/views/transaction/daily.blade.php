@extends('layouts.master')
@section('style')
    <link href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
    <link href="{{asset('master/js/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.transaction_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.transaction_management')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" class="col-md-12 form-inline" method="POST" id="searchForm">
                    @csrf
                    <input type="hidden" name="change_date" id="change_date">
                    <label for="pagesize" class="control-label">{{__('page.show')}} :</label>
                    <select class="form-control form-control-sm mx-md-2" name="pagesize" id="pagesize">
                        <option value="15" @if($pagesize == '15') selected @endif>15</option>
                        <option value="50" @if($pagesize == '50') selected @endif>50</option>
                        <option value="200" @if($pagesize == '200') selected @endif>200</option>
                        <option value="" @if($pagesize == '1000000') selected @endif>All</option>
                    </select>                    
                    <select class="form-control form-control-sm mr-md-2" name="category" id="search_category">
                        <option value="" hidden>{{__('page.select_category')}}</option>
                        @foreach ($categories as $item)
                            <option value="{{$item->id}}" @if($category == $item->id) selected @endif>{{$item->name}}</option>
                        @endforeach                        
                    </select>
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2 mt-md-0" name="keyword" id="search_keyword" value="{{$keyword}}" placeholder="{{__('page.keyword')}}...">
                    <div class="input-group mt-2 mt-md-0 ml-md-2">
                        <div class="input-group-prepend">
                            <button type="button" id="prev_date" class="input-group-text input-group-text-alt" style="padding:0.2rem .5rem;"> << </button>
                        </div>
                        <input type="text" class="form-control form-control-sm col-md-2 text-center" id="search_period" style="min-width:130px;" value="{{$period}}" name="period" autocomplete="off" />
                        <div class="input-group-append">
                            <button type="button" id="next_date" class="input-group-text input-group-text-alt" style="padding:0.2rem .5rem;"> >> </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2 mt-md-0"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <button type="button" class="btn btn-danger btn-sm mt-2 mt-md-0 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</button>
                    <a href="#" class="btn btn-success btn-sm mt-2 mt-md-0 ml-auto" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
                </form>                
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-7">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>{{__('page.reference_no')}}</th>
                                <th>{{__('page.date')}}</th>
                                <th>{{__('page.category')}}</th>
                                <th>{{__('page.amount')}}</th>
                                <th>{{__('page.type')}}</th>
                                <th>{{__('page.note')}}</th>
                                <th style="width:120px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @php
                                $footer_total_to_pay = $footer_paid = $footer_balance = 0;
                            @endphp                               
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="timestamp">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                    <td class="category" data-id="{{$item->category_id}}">{{$item->category->name ?? ''}}</td>
                                    <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount, 2)}}</td>
                                    <td class="type">
                                        @if($item->type == 1)
                                            <span class="badge badge-primary">{{__('page.expense')}}</span>
                                        @elseif($item->type == 2)
                                            <span class="badge badge-info">{{__('page.incoming')}}</span>
                                        @endif
                                    </td>
                                    <td class="note" data-value="{{$item->note}}">
                                        {{$item->note}}
                                        @if(file_exists($item->attachment))
                                            @php
                                                $path_info = pathinfo($item->attachment);
                                                $attach_ext = $path_info['extension'];
                                            @endphp
                                            @if($attach_ext == 'pdf')
                                                <img class="ez_attach text-primary" src="{{asset('images/attachment.png')}}" height="25" href="{{asset($item->attachment)}}" />
                                            @else
                                                <img class="ez_attach text-primary" src="{{asset($item->attachment)}}" height="30" />
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                <a class="dropdown-item btn-edit" href="#" data-id="{{$item->id}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('transaction.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="10" class="text-center">
                                    <span class="mr-5">{{__('page.total_incoming')}} : {{number_format($total['incoming'], 2)}}</span>
                                    <span class="mr-5">{{__('page.total_expense')}} : {{number_format($total['expense'], 2)}}</span>
                                    <span class="">{{__('page.total_balance')}} : {{number_format($total['incoming'] - $total['expense'], 2)}}</span>
                                </th>
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
                                'category' => $category,
                                'period' => $period,
                                'total' => $total,
                                'pagesize' => $pagesize,
                            ])->links() !!}
                        </div>
                    </div> 
                </div>               
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_transaction')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('transaction.create')}}" id="create_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">                       
                        <div class="form-group">
                            <label class="control-label">{{__('page.reference_no')}}</label>
                            <input class="form-control reference_no" type="text" name="reference_no" required placeholder="{{__('page.reference_no')}}" required>
                        </div>   
                        <div class="form-group">
                            <label class="control-label">{{__('page.date')}}</label>
                            <input class="form-control date datetimepicker" type="text" name="date" value="{{date('Y-m-d H:i')}}" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.category')}}</label>
                            <select class="form-control category" name="category" required>
                                <option value="" hidden>{{__('page.select_category')}}</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>                                           
                        <div class="form-group">
                            <label class="control-label">{{__('page.type')}}</label>
                            <select class="form-control type" name="type" required>
                                <option value="1">{{__('page.expense')}}</option>
                                <option value="2">{{__('page.incoming')}}</option>
                            </select>
                        </div>                                             
                        <div class="form-group">
                            <label class="control-label">{{__('page.amount')}}</label>
                            <input class="form-control amount" type="number" name="amount" min="0" step="0.01" placeholder="{{__('page.amount')}}" required>
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

    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.edit_transaction')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('transaction.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">                       
                        <div class="form-group">
                            <label class="control-label">{{__('page.reference_no')}}</label>
                            <input class="form-control reference_no" type="text" name="reference_no" required placeholder="{{__('page.reference_no')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.date')}}</label>
                            <input class="form-control date datetimepicker" type="text" name="date" value="{{date('Y-m-d H:i')}}" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}" required>
                        </div>  
                        <div class="form-group">
                            <label class="control-label">{{__('page.category')}}</label>
                            <select class="form-control category" name="category" required>
                                <option value="" hidden>{{__('page.select_category')}}</option>
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>                                                 
                        <div class="form-group">
                            <label class="control-label">{{__('page.amount')}}</label>
                            <input class="form-control amount" type="number" name="amount" min="0" step="0.01" placeholder="{{__('page.amount')}}" required>
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
    <script src="{{asset('master/js/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/ezview/EZView.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("input.datetimepicker").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });

            $("#search_period").datepicker({
                dateFormat: 'yy-mm-dd',
            });

            $("#btn-add").click(function(){
                $("#addModal").modal();
            });
            
            $(".btn-edit").click(function(){
                let id = $(this).data('id');
                let reference_no = $(this).parents('tr').find('.reference_no').text().trim();
                let timestamp = $(this).parents('tr').find('.timestamp').text().trim();
                let amount = $(this).parents('tr').find('.amount').data('value');
                let note = $(this).parents('tr').find('.note').data('value');
                let category = $(this).parents('tr').find('.category').data('id');
                $("#edit_form .id").val(id);
                $("#edit_form .reference_no").val(reference_no);
                $("#edit_form .date").val(timestamp);
                $("#edit_form .amount").val(amount);
                $("#edit_form .category").val(category);
                $("#edit_form .note").val(note);
                $("#editModal").modal();
            });

            
            $("#btn-reset").click(function(){
                $("#search_keyword").val('');
                $("#search_category").val('');
                $("#search_period").val('');
            });

            $("#pagesize").change(function(){
                $("#searchForm").submit();
            });

            $("#prev_date").click(function(){
                $('#change_date').val('1');
                $("#searchForm").submit();
            });
            $("#next_date").click(function(){
                $('#change_date').val('2');
                $("#searchForm").submit();
            });
            
            if($(".ez_attach").length){
                $(".ez_attach").EZView();
            }            
        })
    </script>
@endsection
