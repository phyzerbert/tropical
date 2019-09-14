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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.edit_proforma')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.edit_proforma')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-content block-content-full">
                <form action="{{route('sale_proforma.update')}}" method="POST" enctype="multipart/form-data" id="app" style="opacity: 0">
                    @csrf
                    <input type="hidden" name="id" id="sale_proforma_id" value="{{$sale_proforma->id}}">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-form-label">{{__('page.date')}} <span class="text-danger">*</span></label>
                            <input class="form-control datepicker" type="text" name="date" id="sale_date" value="{{date('Y-m-d H:i', strtotime($sale_proforma->timestamp))}}" placeholder="{{__('page.date')}}" autocomplete="off" required>
                                @error('date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('page.reference_number')}} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="reference_number" value="{{$sale_proforma->reference_no}}" required placeholder="{{__('page.reference_number')}}">
                                @error('reference_number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('page.customer')}} <span class="text-danger">*</span></label>
                                <div class="input-group">                                  
                                    <select class="form-control select2" name="customer" id="search_customer" required data-placeholder="{{__('page.select_customer')}}">
                                        <option label="{{__('page.select_customer')}}"></option>
                                        @foreach ($customers as $item)
                                            <option value="{{$item->id}}" @if($sale_proforma->customer_id == $item->id) selected @endif>{{$item->company}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-circle tx-white ml-1" id="btn-add-customer"><i class="fa fa-plus"></i></button>
                                    </div>  
                                </div>
                                @error('customer')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('page.attachment')}}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" data-toggle="custom-file-input" name="attachment" accept="image/*">
                                    <label class="custom-file-label" for="example-file-input-custom">{{__('page.choose_file')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('page.note')}}:</label>
                                <textarea class="form-control" name="note" rows="3" placeholder="{{__('page.note')}}">{{$sale_proforma->note}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div>
                                <h5 class="my-1" style="float:left">{{__('page.items')}}</h5>
                                <a href="#" class="btn btn-sm btn-primary mb-2 add-product" style="float:right" @click="add_item()" title="{{__('page.right_ctrl_key')}}"><div><i class="fa fa-plus"></i>{{__('page.add')}}</div></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="product_order_table">
                                    <thead class="table-success">
                                        <tr>
                                            <th>{{__('page.name')}}</th>
                                            <th>{{__('page.price')}}</th>
                                            <th>{{__('page.quantity')}}</th>
                                            <th>{{__('page.subtotal')}}</th>
                                            <th style="width:30px"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="top-search-form">
                                        <tr v-for="(item,i) in items" :key="i">
                                            <td>
                                                <input type="hidden" name="product_id[]" class="product_id" :value="item.product_id" />
                                                <input type="text" name="product_name[]" class="form-control form-control-sm product" ref="product" v-model="item.product_name_code" required />
                                            </td>
                                            <td><input type="number" class="form-control form-control-sm price" name="price[]" min="0" step="0.01" v-model="item.price" required placeholder="{{__('page.price')}}" /></td>
                                            <td><input type="number" class="form-control form-control-sm quantity" name="quantity[]" v-model="item.quantity" required placeholder="{{__('page.quantity')}}" /></td>
                                            <td class="subtotal">
                                                @{{item.amount | currency}}
                                                <input type="hidden" name="amount[]" :value="item.amount" />
                                                <input type="hidden" name="item_id[]" :value="item.item_id" />
                                            </td>
                                            <td align="center">
                                                <a href="#" class="btn btn-sm btn-success remove-product" @click="remove(i)"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">{{__('page.total')}}</th>
                                            <th class="total_quantity">@{{total.quantity | number}}</th>
                                            <th class="total" colspan="2">@{{total.price | currency}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row my-3">                        
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-5 text-sm-right">{{__('page.discount')}} :</label>
                                        <div class="col-md-7">
                                            <input type="text" name="discount_string" class="form-control" v-model="discount_string" placeholder="{{__('page.discount')}}">
                                            <input type="hidden" name="discount" :value="discount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-5 text-sm-right">{{__('page.shipping')}} :</label>
                                        <div class="col-md-7">
                                            <input type="text" name="shipping_string" class="form-control" v-model="shipping_string" placeholder="{{__('page.shipping')}}">
                                            <input type="hidden" name="shipping" :value="shipping">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-5 text-sm-right">{{__('page.returns')}} :</label>
                                        <div class="col-md-7">
                                            <input type="number" name="returns" class="form-control" min="0" v-model="returns" placeholder="{{__('page.returns')}}">
                                            <input type="hidden" name="grand_total" :value="grand_total">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-deck">
                                <div class="col-md-4 col-lg">
                                    <a class="block block-rounded block-link-shadow border rounded" href="javascript:void(0)">
                                        <div class="block-content block-content-full">
                                            <div class="ml-3">
                                                <p class="font-size-h3 font-w200 text-black mb-0">@{{total.price | currency}}</p>
                                                <p class="font-w600 mt-2 text-uppercase text-muted mb-0">{{__('page.sale')}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-lg">
                                    <a class="block block-rounded block-link-shadow border rounded" href="javascript:void(0)">
                                        <div class="block-content block-content-full">
                                            <div class="ml-3">
                                                <p class="font-size-h3 font-w200 text-black mb-0">@{{discount | currency}}</p>
                                                <p class="font-w600 mt-2 text-uppercase text-muted mb-0">{{__('page.discount')}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-lg">
                                    <a class="block block-rounded block-link-shadow border rounded" href="javascript:void(0)">
                                        <div class="block-content block-content-full">
                                            <div class="ml-3">
                                                <p class="font-size-h3 font-w200 text-black mb-0">@{{shipping | currency}}</p>
                                                <p class="font-w600 mt-2 text-uppercase text-muted mb-0">{{__('page.shipping')}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-lg">
                                    <a class="block block-rounded block-link-shadow border rounded" href="javascript:void(0)">
                                        <div class="block-content block-content-full">
                                            <div class="ml-3">
                                                <p class="font-size-h3 font-w200 text-black mb-0">@{{returns | currency}}</p>
                                                <p class="font-w600 mt-2 text-uppercase text-muted mb-0">{{__('page.returns')}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-lg">
                                    <a class="block block-rounded block-link-shadow border rounded" href="javascript:void(0)">
                                        <div class="block-content block-content-full">
                                            <div class="ml-3">
                                                <p class="font-size-h3 font-w200 text-black mb-0">@{{grand_total | currency}}</p>
                                                <p class="font-w600 mt-2 text-uppercase text-muted mb-0">{{__('page.total')}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="form-layout-footer mt-5 text-right">
                                <button type="submit" class="btn btn-primary mr-2"><i class="fa fa-check"></i>  {{__('page.save')}}</button>
                                <a href="{{route('sale_proforma.index')}}" class="btn btn-warning"><i class="fa fa-times"></i>  {{__('page.cancel')}}</a>
                            </div>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>

    <div class="modal fade" id="addcustomerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_customer')}}</h4>
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
                        placeholder: "{!! __('page.product_customer') !!}"
                    });                    
            });

            $("#btn-add-customer").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addcustomerModal").modal();
            });

            $("#btn_create").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('customer.ajax_create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        $("#ajax-loading").hide();
                        if(data.id != null) {
                            $("#addcustomerModal").modal('hide');
                            $("#search_customer").append(`
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
    <script src="{{asset('master/js/custom/edit_sale_proforma.js')}}"></script>
@endsection




