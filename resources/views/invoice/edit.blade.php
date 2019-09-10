@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('master/js/plugins/select2/css/select2.min.css')}}">
    <script src="{{asset('master/js/plugins/vuejs/vue.js')}}"></script>
    <script src="{{asset('master/js/plugins/vuejs/axios.js')}}"></script>
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.edit_invoice')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.edit_invoice')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-content block-content-full">
                <form action="{{route('invoice.update')}}" method="POST" enctype="multipart/form-data" id="app" style="opacity: 0">
                    @csrf
                    <input type="hidden" name="id" id="invoice_id" value="{{$invoice->id}}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.reference_no')}}: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="reference_no" value="{{$invoice->reference_no}}" placeholder="{{__('page.reference_no')}}" required>
                                @error('reference_no')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.supplier')}}:</label>
                                <div class="input-group">                                  
                                    <select class="form-control select2" name="supplier" id="search_supplier" required data-placeholder="{{__('page.select_supplier')}}">
                                        <option label="{{__('page.select_supplier')}}"></option>
                                        @foreach ($suppliers as $item)
                                            <option value="{{$item->id}}" @if($invoice->supplier_id == $item->id) selected @endif>{{$item->company}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn bd bg-info rounded-circle text-white ml-1" id="btn-add-supplier"><i class="fa fa-plus"></i></button>
                                    </span>  
                                </div>
                                @error('supplier')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">{{__('page.issue_date')}}: <span class="tx-danger">*</span></label>
                            <input class="datepicker form-control" type="text" name="issue_date" value="{{$invoice->issue_date}}" placeholder="{{__('page.issue_date')}}" autocomplete="off" required>
                            @error('issue_date')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.due_date')}}: <span class="tx-danger">*</span></label>
                                <input class="form-control datepicker" type="text" name="due_date" value="{{$invoice->due_date}}" placeholder="{{__('page.due_date')}}" autocomplete="off" required>
                                @error('due_date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.customers_vat')}}</label>
                                <input class="form-control" type="text" name="customers_vat" value="{{$invoice->customers_vat}}" required placeholder="{{__('page.customers_vat')}}">
                                @error('customers_vat')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.delivery_date')}}: <span class="tx-danger">*</span></label>
                                <input class="form-control datepicker" type="text" name="delivery_date" value="{{$invoice->delivery_date}}" placeholder="{{__('page.delivery_date')}}" autocomplete="off" required>
                                @error('delivery_date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.concerning_week')}}</label>
                                <input class="form-control" type="text" name="concerning_week" value="{{ $invoice->concerning_week }}" required placeholder="{{__('page.concerning_week')}}">
                                @error('concerning_week')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.shipment')}}</label>
                                <input class="form-control" type="text" name="shipment" value="{{ $invoice->shipment }}" required placeholder="{{__('page.shipment')}}">
                                @error('shipment')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.vessel')}}</label>
                                <input class="form-control" type="text" name="vessel" value="{{ $invoice->vessel }}" required placeholder="{{__('page.vessel')}}">
                                @error('vessel')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.port_of_discharge')}}</label>
                                <input class="form-control" type="text" name="port_of_discharge" value="{{ $invoice->port_of_discharge }}" required placeholder="{{__('page.port_of_discharge')}}">
                                @error('port_of_discharge')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.origin')}}</label>
                                <input class="form-control" type="text" name="origin" value="{{ $invoice->origin }}" required placeholder="{{__('page.origin')}}">
                                @error('origin')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">{{__('page.image')}}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="image" accept="image/*">
                                    <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
                                </div>                               
                            </div>
                        </div>
                    </div> 
                    <div class="row mg-b-25">
                        <div class="col-md-12">
                            <div>
                                <h3 class="mb-2" style="float:left">{{__('page.items')}}</h3>
                                {{-- <button type="button" class="btn btn-primary mg-b-10 add-product" style="float:right">ADD</button> --}}
                            <a href="#" class="btn btn-sm btn-primary btn-icon mb-2 add-item" style="float:right" @click="add_item()"><i class="fa fa-plus"></i> {{__('page.add')}}</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="item_table">
                                    <thead class="table-success">
                                        <tr>
                                            <th>{{__('page.product_code')}}</th>
                                            <th>{{__('page.quantity')}}</th>
                                            <th>{{__('page.price')}}</th>
                                            <th>{{__('page.amount')}}</th>
                                            <th>{{__('page.surcharge_reduction')}}</th>
                                            <th>{{__('page.total_amount')}}</th>
                                            <th style="width:30px"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="top-search-form">
                                        <tr v-for="(item,i) in items" :key="i">
                                            <td>
                                                <input type="hidden" name="product_id[]" class="product_id" :value="item.product_id" />
                                                <input type="text" name="product_name[]" class="form-control form-control-sm product" ref="product" v-model="item.product_code" required />
                                            </td>
                                            <td><input type="number" class="form-control form-control-sm quantity" name="quantity[]" v-model="item.quantity" required placeholder="{{__('page.quantity')}}" /></td>
                                            <td><input type="number" class="form-control form-control-sm price" name="price[]" step="0.01" v-model="item.price" required placeholder="{{__('page.price')}}" /></td>
                                            <td class="amount">
                                                @{{formatPrice(item.amount)}}
                                                <input type="hidden" name="amount[]" :value="item.amount" />
                                            </td>
                                            <td class="surcharge_reduction">
                                                <input type="number" class="form-control form-control-sm surcharge_reduction" name="surcharge_reduction[]" v-model="item.surcharge_reduction" required placeholder="{{__('page.surcharge_reduction')}}" />
                                            </td>
                                            <td class="total_amount">
                                                @{{formatPrice(item.total_amount)}}
                                                <input type="hidden" name="total_amount[]" :value="item.total_amount" />
                                                <input type="hidden" name="item_id[]" :value="item.item_id" />
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-warning remove-product" @click="remove(i)"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right">{{__('page.total')}}</th>
                                            <th class="total_quantity">@{{formatPrice(total.quantity)}}</th>
                                            <th colspan="3" class="text-right">Total Excluding VAT</th>
                                            <th colspan="2" class="total_excluding_vat">@{{formatPrice(total.amount)}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">V.A.T</th>
                                            <th colspan="2">
                                                @{{formatPrice(vat)}}
                                                <input type="hidden" name="vat_amount" :value="vat" />
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">Total Including VAT</th>
                                            <th colspan="2">@{{formatPrice(total.amount - vat)}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="text-right">Total To Pay</th>
                                            <th colspan="2">
                                                @{{formatPrice(total_to_pay)}}
                                                <input type="hidden" name="total_to_pay" :value="total_to_pay" />
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">{{__('page.note')}}:</label>
                                <textarea class="form-control" name="note" rows="3" placeholder="{{__('page.note')}}">{{ $invoice->note }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-layout-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check mg-r-2"></i> {{__('page.save')}}</button>
                        <a href="{{route('invoice.index')}}" class="btn btn-warning"><i class="fa fa-times mg-r-2"></i> {{__('page.cancel')}}</a>
                    </div>
                </form>                
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSupplierModal">
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

@endsection


@section('script')
    <script src="{{asset('master/js/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
            });

            $('.select2').each(function() {
                $(this)
                    .wrap('<div class="position-relative" style="width: calc(100% - 45px)"></div>')
                    .select2({
                        width: 'resolve',
                        placeholder: "{!! __('page.product_supplier') !!}"
                    });                    
            });

            $("#btn-add-supplier").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addSupplierModal").modal();
            });

            $("#btn_create").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('supplier.ajax_create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        $("#ajax-loading").hide();
                        if(data.id != null) {
                            $("#addSupplierModal").modal('hide');
                            $("#search_supplier").append(`
                                <option value="${data.id}">${data.company}</option>
                            `).val(data.id);
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
        });
    </script>
    <script src="{{asset('master/js/custom/edit_invoice.js')}}"></script>
@endsection




