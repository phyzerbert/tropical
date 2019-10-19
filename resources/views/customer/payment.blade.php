@extends('layouts.master')
@section('style')    
    <link href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">  
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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-people"></i> {{__('page.customer_payments')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item" aria-current="page">{{__('page.customer')}}</li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.payment_list')}}</li>
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
                    <input type="text" class="form-control form-control-sm col-md-2 mt-2 mt-md-0" name="reference_no" id="search_reference_no" value="{{$reference_no}}" placeholder="{{__('page.reference_no')}}">
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2 mt-md-0"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <button type="button" class="btn btn-danger btn-sm mt-2 mt-md-0 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</button>
                    <a href="{{route('customer.sales', $customer->id)}}" class="btn btn-outline-info btn-sm mt-2 mt-md-0 ml-auto"><i class="fa fa-credit-card"></i> {{__('page.sales')}}</a>
                    <a href="{{route('customer.payments', $customer->id)}}" class="btn btn-info btn-sm mt-2 mt-md-0 ml-2"><i class="far fa-money-bill-alt"></i> {{__('page.payments')}}</a>
                </form>                
            </div>
            <div class="block-content block-content-full">
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
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class="date">{{date('Y-m-d', strtotime($item->timestamp))}}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount)}}</td>
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
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {!! $data->appends([
                                'reference_no' => $reference_no,
                                'period' => $period,
                                'pagesize' => $pagesize,
                            ])->links() !!}
                        </div>
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
    <script src="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/ezview/EZView.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#edit_form input.date").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let date = $(this).parents('tr').find(".date").text().trim();
                let reference_no = $(this).parents('tr').find(".reference_no").text().trim();
                let amount = $(this).parents('tr').find(".amount").data('value');
                let note = $(this).parents('tr').find(".note").text().trim();
                $("#editModal input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .date").val(date);
                $("#editModal .reference_no").val(reference_no);
                $("#editModal .amount").val(amount);
                $("#editModal .note").val(note);
                $("#editModal").modal();
            });

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
            $("#pagesize").change(function(){
                $("#searchForm").submit();
            });
            // $(".ez_attach").EZView();
            $(".ez_attach1").EZView();
        })
    </script>
@endsection
