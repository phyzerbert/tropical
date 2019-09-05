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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-people"></i> {{__('page.payment_list')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.payment_list')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
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
                                <th>{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>                                
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class="date">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount)}}</td>
                                    <td class="" data-path="{{$item->attachment}}">
                                        <span class="tx-info note">{{$item->note}}</span>&nbsp;
                                        @if($item->attachment != "")
                                            <a href="#" class="attachment" data-value="{{asset($item->attachment)}}"><i class="fa fa-paperclip"></i></a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" title="" data-original-title="{{__('page.edit')}}">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <a href="{{route('payment.delete', $item->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        @if($type == 'invoice')
                            <a href="{{route('invoice.create')}}" class="btn btn-oblong btn-primary mr-3"><i class="fa fa-plus"></i>  {{__('page.add_invoice')}}</a>                                       
                            <a href="{{route('invoice.index')}}" class="btn btn-oblong btn-success"><i class="fa fa-list"></i>  {{__('page.invoices')}}</a>
                        @elseif($type == 'proforma')
                            <a href="{{route('proforma.create')}}" class="btn btn-oblong btn-primary mr-3"><i class="fa fa-plus"></i>  {{__('page.add_proforma')}}</a>                                       
                            <a href="{{route('proforma.index')}}" class="btn btn-oblong btn-success"><i class="fa fa-list"></i>  {{__('page.proforma')}}</a>
                        @endif
                    </div>
                </div>                               
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.edit_payment')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('payment.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('page.date')}}</label>
                                <input class="form-control date" type="text" name="date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}">
                            </div>                        
                            <div class="form-group">
                                <label class="control-label">{{__('page.reference_no')}}</label>
                                <input class="form-control reference_no" type="text" name="reference_no" placeholder="{{__('page.reference_number')}}">
                            </div>                                                
                            <div class="form-group">
                                <label class="control-label">{{__('page.amount')}}</label>
                                <input class="form-control amount" type="text" name="amount" placeholder="{{__('page.amount')}}">
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
                        <button type="submit" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachModal">
        <div class="modal-dialog" style="margin-top:17vh">
            <div class="modal-content">
                <div id="image_preview"></div>
                {{-- <img src="" id="attachment" width="100%" height="600" alt=""> --}}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('master/js/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/js/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
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
        })
    </script>
@endsection
