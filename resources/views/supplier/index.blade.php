@extends('layouts.master')
@section('style')    
    <link rel="stylesheet" href="{{asset('master/js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('master/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-handbag"></i> {{__('page.supplier_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.supplier_management')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">            
            <div class="block-header block-header-default justify-content-end">
                <button type="button" class="btn btn-success btn-sm ml-3 float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</button>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">                    
                    <table class="table table-striped table-bordered table-hover" id="suppliersTable">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>{{__('page.name')}}</th>
                                <th>{{__('page.company')}}</th>
                                <th>{{__('page.phone')}}</th>
                                <th>{{__('page.email_address')}}</th>
                                <th style="width:150px !important;">{{__('page.total_amount')}}</th>
                                <th>{{__('page.paid')}}</th>
                                <th>{{__('page.balance')}}</th>
                                <th style="width:150px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $footer_total_purchases = $footer_total_amount = $footer_paid = 0;
                            @endphp                               
                            @foreach ($data as $item)
                                @php
                                    $invoice_array = $item->invoices->pluck('id');
                                    $mod_total_amount = 0;
                                    $mod_paid = \App\Models\Payment::whereIn('invoice_id', $invoice_array);

                                    $total_amount = $item->invoices->sum('total_to_pay');
                                    $paid = $mod_paid->sum('amount');  

                                    $footer_total_amount += $total_amount;
                                    $footer_paid += $paid;
                                @endphp                              
                                <tr>
                                    <input type="hidden" name="note" class="note" value="{{$item->note}}">
                                    <input type="hidden" name="address" class="address" value="{{$item->address}}">
                                    <input type="hidden" name="city" class="city" value="{{$item->city}}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class="name">{{$item->name}}</td>
                                    <td class="company">{{$item->company}}</td>
                                    <td class="phone_number">{{$item->phone_number}}</td>
                                    <td class="email">{{$item->email}}</td>
                                    <td class="total_amount">{{number_format($total_amount)}}</td>                                        
                                    <td>{{number_format($paid)}}</td>
                                    <td>{{number_format($total_amount - $paid)}}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                <a class="dropdown-item" href="{{route('supplier.invoices', $item->id)}}">{{__('page.view_reports')}}</a>
                                                <a class="dropdown-item" href="{{route('supplier.report', $item->id)}}">{{__('page.report')}}</a>
                                                <a class="dropdown-item btn-edit" href="javascript:;" data-id="{{$item->id}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('supplier.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">{{__('page.total')}}</td>
                                <td>{{number_format($footer_total_amount)}}</td>
                                <td>{{number_format($footer_paid)}}</td>
                                <td>{{number_format($footer_total_amount - $footer_paid)}}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_supplier')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="create_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                            <span id="name_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.company')}}</label>
                            <input class="form-control company" type="text" name="company" placeholder="{{__('page.company_name')}}">
                            <span id="company_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.email')}}</label>
                            <input class="form-control email" type="email" name="email" placeholder="{{__('page.email_address')}}">
                            <span id="email_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.phone_number')}}</label>
                            <input class="form-control phone_number" type="text" name="phone_number" placeholder="{{__('page.phone_number')}}">
                            <span id="phone_number_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.address')}}</label>
                            <input class="form-control address" type="text" name="address" placeholder="{{__('page.address')}}">
                            <span id="address_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.city')}}</label>
                            <input class="form-control city" type="text" name="city" placeholder="{{__('page.city')}}">
                            <span id="city_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.note')}}</label>
                            <textarea class="form-control note" name="note" rows="3" placeholder="{{__('page.note')}}"></textarea>
                            <span id="note_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button type="button" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
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
                    <h4 class="modal-title">{{__('page.edit_supplier')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="edit_form" method="post">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                            <span id="edit_name_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.company')}}</label>
                            <input class="form-control company" type="text" name="company" placeholder="{{__('page.company_name')}}">
                            <span id="edit_company_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.email')}}</label>
                            <input class="form-control email" type="email" name="email" placeholder="{{__('page.email_address')}}">
                            <span id="edit_email_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.phone_number')}}</label>
                            <input class="form-control phone_number" type="text" name="phone_number" placeholder="{{__('page.phone_number')}}">
                            <span id="edit_phone_number_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.address')}}</label>
                            <input class="form-control address" type="text" name="address" placeholder="{{__('page.address')}}">
                            <span id="edit_address_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.city')}}</label>
                            <input class="form-control city" type="text" name="city" placeholder="{{__('page.city')}}">
                            <span id="edit_city_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">{{__('page.note')}}</label>
                            <textarea class="form-control note" name="note" rows="3" placeholder="{{__('page.note')}}"></textarea>
                            <span id="edit_note_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                    </div>  
                    <div class="modal-footer">
                        <button type="button" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="{{asset('master/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('master/js/plugins/datatables/buttons/dataTables.buttons.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#suppliersTable").dataTable({
                sWrapper: "dataTables_wrapper dt-bootstrap4",
                sFilterInput: "form-control",
                sLengthSelect: "form-control",
                language: {
                    lengthMenu: "_MENU_",
                    search: "_INPUT_",
                    searchPlaceholder: "Search..",
                    info: "Page <strong>_PAGE_</strong> of <strong>_PAGES_</strong>",
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        previous: '<i class="fa fa-angle-left"></i>',
                        next: '<i class="fa fa-angle-right"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>'
                    }
                },
                pageLength: 15,
                lengthMenu: [
                    [15, 50, 200, -1],
                    [15, 50, 200, "All"]
                ],
                autoWidth: !1
            })

            $("#btn-add").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addModal").modal();
            });

            $("#btn_create").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('supplier.create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        $("#ajax-loading").hide();
                        if(data == 'success') {
                            alert("{{__('page.created_successfully')}}");
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        console.log(data.responseJSON);
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#name_error').show();
                                $('#create_form .name').focus();
                            }
                            
                            if(messages.company) {
                                $('#company_error strong').text(data.responseJSON.errors.company[0]);
                                $('#company_error').show();
                                $('#create_form .company').focus();
                            }

                            if(messages.email) {
                                $('#email_error strong').text(data.responseJSON.errors.email[0]);
                                $('#email_error').show();
                                $('#create_form .email').focus();
                            }

                            if(messages.phone_number) {
                                $('#phone_number_error strong').text(data.responseJSON.errors.phone_number[0]);
                                $('#phone_number_error').show();
                                $('#create_form .phone_number').focus();
                            }

                            if(messages.address) {
                                $('#address_error strong').text(data.responseJSON.errors.address[0]);
                                $('#address_error').show();
                                $('#create_form .address').focus();
                            }

                            if(messages.city) {
                                $('#city_error strong').text(data.responseJSON.errors.city[0]);
                                $('#city_error').show();
                                $('#create_form .city').focus();
                            }
                        }
                    }
                });
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let name = $(this).parents('tr').find(".name").text().trim();
                let company = $(this).parents('tr').find(".company").text().trim();
                let email = $(this).parents('tr').find(".email").text().trim();
                let phone_number = $(this).parents('tr').find(".phone_number").text().trim();
                let address = $(this).parents('tr').find(".address").val().trim();
                let city = $(this).parents('tr').find(".city").val().trim();
                let note = $(this).parents('tr').find(".note").val().trim();

                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .name").val(name);
                $("#editModal .company").val(company);
                $("#editModal .email").val(email);
                $("#editModal .phone_number").val(phone_number);
                $("#editModal .address").val(address);
                $("#editModal .city").val(city);
                $("#editModal .note").val(note);

                $("#editModal").modal();
            });

            $("#btn_update").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('supplier.edit')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#edit_form').serialize(),
                    success : function(data) {
                        $("#ajax-loading").hide();
                        if(data == 'success') {
                            alert("{{__('page.updated_successfully')}}");
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#edit_name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#edit_name_error').show();
                                $('#edit_form .name').focus();
                            }
                            
                            if(messages.company) {
                                $('#edit_company_error strong').text(data.responseJSON.errors.company[0]);
                                $('#edit_company_error').show();
                                $('#edit_form .company').focus();
                            }

                            if(messages.email) {
                                $('#edit_email_error strong').text(data.responseJSON.errors.email[0]);
                                $('#edit_email_error').show();
                                $('#edit_form .email').focus();
                            }

                            if(messages.phone_number) {
                                $('#edit_phone_number_error strong').text(data.responseJSON.errors.phone_number[0]);
                                $('#edit_phone_number_error').show();
                                $('#edit_form .phone_number').focus();
                            }

                            if(messages.address) {
                                $('#edit_address_error strong').text(data.responseJSON.errors.address[0]);
                                $('#edit_address_error').show();
                                $('#edit_form .address').focus();
                            }

                            if(messages.city) {
                                $('#edit_city_error strong').text(data.responseJSON.errors.city[0]);
                                $('#edit_city_error').show();
                                $('#edit_form .city').focus();
                            }
                        }
                    }
                });
            });
        })
    </script>
@endsection




