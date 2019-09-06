@extends('layouts.master')
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
                            <p class="font-size-sm description" title="{{ $item->description }}">
                                {{ $item->description }}
                            </p>
                        </div>
                        <div class="block-content block-content-full bg-body-light">
                            <div class="row no-gutters font-size-sm text-center">
                                <div class="col-6">
                                    <button type="button" data-id="{{$item->id}}" data-code="{{$item->code}}" data-description="{{$item->description}}" class="btn btn-sm btn-outline-primary btn-edit" style="min-width: 85px">
                                        <i class="fa fa-fw fa-edit mr-1"></i> {{__('page.edit')}}
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a href="{{route('product.delete', $item->id)}}" class="btn btn-sm btn-outline-danger" style="min-width: 85px" onclick="return window.confirm('{{__('page.are_you_sure')}}')">
                                        <i class="fa fa-fw fa-times mr-1"></i> {{__('page.delete')}}
                                    </a>
                                </div>
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
        <div class="modal-dialog">
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
                            <label class="control-label">{{__('page.description')}}</label>
                            <input class="form-control description" type="text" name="description" placeholder="{{__('page.description')}}">
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
        <div class="modal-dialog">
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
                            <label class="control-label">{{__('page.description')}}</label>
                            <input class="form-control description" type="text" name="description" placeholder="{{__('page.description')}}">
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

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $("#btn-add").click(function(){
                $("#create_form input.form-control").val('');
                $("#addModal").modal();
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let code = $(this).data("code");
                let description = $(this).data("description");
                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .code").val(code);
                $("#editModal .description").val(description);
                $("#editModal").modal();
            });

        })
    </script>
@endsection
