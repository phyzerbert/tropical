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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.edit_shipment')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.edit_shipment')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="block block-rounded block-bordered" id="app" style="opacity: 0">
            <form action="{{route('sale_shipment.update')}}" method="post">
                <div class="block-header block-header-default pb-3">
                    <div class="form-inline col-md-12">
                        <label for="" class="mr-3 ml-md-auto" style="margin-top:18px;">PRO-FORMA</label>
                        <input type="text" class="form-control form-control-sm col-md-2 mt-sm-3 mr-3" name="invoice" style="width:200px;" value="{{$shipment->reference_no}}" placeholder="PRO-FORMA" />
                        <label for="" class="mr-3" style="margin-top:18px;">{{__('page.week_c')}}</label>
                        <input type="text" class="form-control form-control-sm col-md-2 mt-sm-3 mr-3" name="week_c" style="width:200px;" value="{{$shipment->week_c}}" placeholder="{{__('page.week_c')}}" />
                        <button type="button" class="btn btn-sm btn-primary btn-icon mt-3 add-item" @click="add_item()"><i class="fa fa-plus"></i> {{__('page.add_product')}}</button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    @csrf
                    <input type="hidden" name="id" value="{{$shipment->id}}" id="invoice_id" />
                    <div class="table-responsive">                        
                        <table class="table table-bordered table-colored" id="item_table">
                            <thead class="table-success">
                                <tr>
                                    <th>{{__('page.product_code')}}</th>
                                    <th>{{__('page.product_name')}}</th>
                                    <th>{{__('page.quantity')}}</th>
                                    <th>{{__('page.price')}}</th>
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
                                    <td>@{{item.product_name}}</td>
                                    <td><input type="number" class="form-control form-control-sm quantity" name="quantity[]" v-model="item.quantity" required placeholder="{{__('page.quantity')}}" /></td>
                                    <td><input type="number" class="form-control form-control-sm price" name="price[]" step="0.01" v-model="item.price" required placeholder="{{__('page.price')}}" /></td>
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
                                    <th colspan="4" class="text-right">{{__('page.total')}}</th>
                                    <th colspan="2" class="total_excluding_vat">@{{formatPrice(total.amount)}}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">{{__('page.total_to_pay')}}</th>
                                    <th colspan="2">
                                        @{{formatPrice(total_to_pay)}}
                                        <input type="hidden" name="total_to_pay" :value="total_to_pay" />
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane mr-2"></i> {{__('page.save')}}</button>
                    </div>                
                </div>
            </form>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{asset('master/js/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            
        })
    </script>
    <script src="{{asset('master/js/custom/edit_sale_shipment.js')}}"></script>
@endsection
