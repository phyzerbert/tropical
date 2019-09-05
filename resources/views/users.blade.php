@extends('layouts.master')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-people"></i> {{__('page.user_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.user_management')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default justify-content-end">
                <button type="button" class="btn btn-success btn-sm float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</button>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>{{__('page.username')}}</th>
                                <th>{{__('page.first_name')}}</th>
                                <th>{{__('page.last_name')}}</th>
                                <th style="width:120px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>                                
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="name">{{$item->name}}</td>
                                    <td class="first_name">{{$item->first_name}}</td>
                                    <td class="last_name">{{$item->last_name}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" title="" data-original-title="{{__('page.edit')}}">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <a href="{{route('user.delete', $item->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">
                                                <i class="fa fa-times"></i>
                                            </a>
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
                            {!! $data->appends(['name' => $name])->links() !!}
                        </div>
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
                    <h4 class="modal-title">{{__('page.add_new_user')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="create_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.username')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="Username">
                            <span class="invalid-feedback name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.first_name')}}</label>
                            <input class="form-control first_name" type="text" name="first_name" placeholder="{{__('page.first_name')}}">
                            <span class="invalid-feedback first_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.last_name')}}</label>
                            <input class="form-control last_name" type="text" name="last_name" placeholder="{{__('page.last_name')}}">
                            <span class="invalid-feedback last_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password')}}</label>
                            <input type="password" name="password" class="form-control password" placeholder="{{__('page.password')}}">
                            <span class="invalid-feedback password_error">
                                <strong></strong>
                            </span>
                        </div>    
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password_confirm')}}</label>
                            <input type="password" name="password_confirmation" class="form-control confirm_password" placeholder="{{__('page.password_confirm')}}">
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button type="button" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.edit_user')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="edit_form" method="post">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.username')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="Username">
                            <span class="invalid-feedback name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.first_name')}}</label>
                            <input class="form-control first_name" type="text" name="first_name" placeholder="{{__('page.first_name')}}">
                            <span class="invalid-feedback first_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.last_name')}}</label>
                            <input class="form-control last_name" type="text" name="last_name" placeholder="{{__('page.last_name')}}">
                            <span class="invalid-feedback last_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password')}}</label>
                            <input type="password" name="password" class="form-control password" placeholder="{{__('page.password')}}">
                            <span class="invalid-feedback password_error">
                                <strong></strong>
                            </span>
                        </div>    
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password_confirm')}}</label>
                            <input type="password" name="password_confirmation" class="form-control confirm_password" placeholder="{{__('page.password_confirm')}}">
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <button type="button" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{__('page.close')}}</button>
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
                $("#create_form .invalid-feedback strong").text('');
                $("#addModal").modal();
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let name = $(this).parents('tr').find(".name").text().trim();
                let first_name = $(this).parents('tr').find(".first_name").text().trim();
                let last_name = $(this).parents('tr').find(".last_name").text().trim();
                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .name").val(name);
                $("#editModal .first_name").val(first_name);
                $("#editModal .last_name").val(last_name);
                $("#editModal").modal();
            });

            $("#btn_create").click(function(){  
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('user.create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        if(data == 'success') {
                            alert('Created successfully.');
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                        $("#ajax-loading").hide();
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#create_form .name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#create_form .name_error').show();
                                $('#create_form .name').focus();
                            }

                            if(messages.password) {
                                $('#create_form .password_error strong').text(data.responseJSON.errors.password[0]);
                                $('#create_form .password_error').show();
                                $('#create_form .password').focus();
                            }
                        }
                    }
                });
            });

            $("#btn_update").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('user.edit')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#edit_form').serialize(),
                    success : function(data) {
                        console.log(data);
                        if(data == 'success') {
                            alert('Updated successfully.');
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                        $("#ajax-loading").hide();
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#edit_form .name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#edit_form .name_error').show();
                                $('#edit_form .name').focus();
                            }

                            if(messages.password) {
                                $('#edit_form .password_error strong').text(data.responseJSON.errors.password[0]);
                                $('#edit_form .password_error').show();
                                $('#edit_form .password').focus();
                            }
                        }
                    }
                });
            });
        })
    </script>
@endsection
