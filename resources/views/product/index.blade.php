@extends('layouts.master')
@section('style')    
    <link href="{{asset('master/js/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-present"></i> {{__('page.product_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.product_management')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="clearfix">
            <form action="" class="form-inline float-left">
                @csrf
                <input type="text" class="form-control form-control-sm mt-2" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                <button type="submit" class="btn btn-sm btn-primary mt-2 ml-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
            </form>            
            <button type="button" class="btn btn-success btn-sm mt-2 float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</button>
        </div>
        <hr />    
        <div class="row">
            @foreach ($data as $item)
                <div class="col-lg-3">
                    <div class="block block-rounded block-link-pop">
                        <div class="p-3">                                
                            <img class="img-fluid" height="300" src="@if($item->image){{asset($item->image)}}@else{{asset('images/no-image.jpg')}}@endif" alt="">
                        </div>
                        <div class="block-content">
                            <h4 class="mb-1">{{ $item->code }}</h4>
                            <p class="font-size-sm name" title="{{ $item->name }}">
                                {{ $item->name }}
                            </p>
                        </div>
                        <div class="block-content block-content-full bg-body-light">
                            <div class="d-flex justify-content-around">
                                <button type="button" data-id="{{$item->id}}" class="btn btn-sm btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('page.edit')}}">
                                    <i class="fa fa-fw fa-edit"></i>
                                </button>
                                <button type="button" data-id="{{$item->id}}" class="btn btn-sm btn-outline-info btn-description" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('page.description')}}">
                                    <i class="fa fa-fw fa-eye"></i>
                                </button>
                                <a href="{{route('product.delete', $item->id)}}" class="btn btn-sm btn-outline-danger" onclick="return window.confirm('{{__('page.are_you_sure')}}')" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{__('page.delete')}}">
                                    <i class="fa fa-fw fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach            
        </div>
        <div class="row">
            <div class="col-12">
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

    <!-- The Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_product')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('product.create')}}" id="create_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.code')}}</label>
                            <input class="form-control code" type="text" name="code" placeholder="{{__('page.code')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.description')}}</label>
                            <textarea class="form-control description" name="description" placeholder="{{__('page.description')}}">
                                {{-- <p style="text-align: center; ">
                                    <b>ESPECIFICACIONES DE CALIDAD FRUTA-2019/FRUIT SPECS 2019</b>
                                </p> --}}
                                {{-- <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.image')}}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="image" accept="image/*">
                                <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
                            </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.edit_product')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('product.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.code')}}</label>
                            <input class="form-control code" type="text" name="code" placeholder="{{__('page.code')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.description')}}</label>
                            <textarea class="form-control description" name="description" placeholder="{{__('page.description')}}"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.image')}}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="image" accept="image/*">
                                <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
                            </div>
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
    <div class="modal fade" id="descriptionModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.description')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>                
                <div class="modal-body">
                    <div class="p-3" id="description_body"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('master/js/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(".modal .description").summernote();
            $("#btn-add").click(function(){
                $("#create_form input.form-control").val('');
                $("#addModal").modal();
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                $.ajax({
                    url: "{{route('get_product')}}",
                    type: "POST",
                    data: {id : id},
                    success: function(data){
                        $("#edit_form input.form-control").val('');
                        $("#editModal .id").val(id);
                        $("#editModal .code").val(data.code);
                        $("#editModal .name").val(data.name);
                        $("#editModal .description").summernote("code", data.description);
                        $("#editModal").modal();
                    },
                    error(error){
                        console.log(error);
                        alert("Something went wrong!");
                    }
                });                
            });

            
            $(".btn-description").click(function(){
                let id = $(this).data("id");
                $.ajax({
                    url: "{{route('get_product')}}",
                    type: "POST",
                    data: {id : id},
                    success: function(data){
                        $("#description_body").html('');
                        $("#description_body").html(data.description);
                        $("#descriptionModal").modal('show');
                    },
                    error(error){
                        console.log(error);
                        alert("Something went wrong!");
                    }
                });                
            });
        })
    </script>
@endsection
