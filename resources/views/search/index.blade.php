@extends('layouts.master')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="fa fa-search"></i> {{__('page.search')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.search')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">  
        <div class="row flex-md-10-auto">
            <div class="col-md-4 col-lg-5 col-xl-3">
                @include('search.filter')
            </div>
            <div class="col-md-8 col-lg-7 col-xl-9">
                <div class="block block-fx-pop">
                    <div class="block-content">
                        @include('elements.pagesize')
                        <div class="table-responsive pt-4 pb-8">
                            <table class="table table-bordered table-vcenter">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PRO-FORMA INVOICE NE</th>
                                        @if(in_array('identification', $search_params))<th>IDENTIFICACION O NIT</th>@endif
                                        <th>{{__('page.week_c')}}</th>
                                        <th>{{__('page.week_d')}}</th>
                                        @if(in_array('container', $search_params))<th>{{__('page.container')}}</th>@endif
                                        @if(in_array('booking', $search_params))<th>{{__('page.booking')}}</th>@endif
                                        @if(in_array('bl', $search_params))<th>BL</th>@endif
                                        @if(in_array('shipping_company', $search_params))<th>{{__('page.shipping_company')}}</th>@endif
                                        @if(in_array('temperature', $search_params))<th>{{__('page.temperature')}}</th>@endif
                                        @if(in_array('damper', $search_params))<th>{{__('page.damper')}}</th>@endif
                                        @if(in_array('type_of_merchandise', $search_params))<th>{{__('page.type_of_merchandise')}}</th>@endif
                                        @if(in_array('fruit_loading_date', $search_params))<th>{{__('page.fruit_loading_date')}}</th>@endif
                                        @if(in_array('ship_departure_date', $search_params))<th>{{__('page.ship_departure_date')}}</th>@endif
                                        @if(in_array('estimated_date', $search_params))<th>{{__('page.estimated_date_of_shipping_company')}}</th>@endif
                                        @if(in_array('agency', $search_params))<th>{{__('page.agency')}}</th>@endif
                                        @if(in_array('company', $search_params))<th>{{__('page.company')}}</th>@endif
                                        @if(in_array('dock', $search_params))<th>{{__('page.dock')}}</th>@endif
                                        <th>{{__('page.products')}}</th>                               
                                        <th>TOTAL CONTENEDOR</th>
                                        <th>PESO CARGA</th>
                                        <th>TARA</th>
                                        <th>VGM</th>
                                        <th width="100">{{__('page.detail')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $footer_product_array = array();
                                    @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td>@isset($item->proforma->reference_no){{$item->proforma->reference_no}}@endisset</td>
                                            @if(in_array('identification', $search_params))<td>{{$item->identification_or_nit}}</td>@endif
                                            <td>{{$item->week_c}}</td>
                                            <td>{{$item->week_d}}</td>
                                            @if(in_array('container', $search_params))<td>{{$item->container}}</td>@endif
                                            @if(in_array('booking', $search_params))<td>{{$item->booking}}</td>@endif
                                            @if(in_array('bl', $search_params))<td>{{$item->bl}}</td>@endif
                                            @if(in_array('shipping_company', $search_params))<td>{{$item->shipping_company}}</td>@endif
                                            @if(in_array('temperature', $search_params))<td>{{$item->temperatura}} °C</td>@endif
                                            @if(in_array('damper', $search_params))<td>{{$item->damper}}</td>@endif
                                            @if(in_array('type_of_merchandise', $search_params))<td>{{$item->type_of_merchandise}}</td>@endif
                                            @if(in_array('fruit_loading_date', $search_params))<td>@if($item->fruit_loading_date){{$item->fruit_loading_date}}@endif</td>@endif
                                            @if(in_array('ship_departure_date', $search_params))<td>@if($item->ship_departure_date){{$item->ship_departure_date}}@endif</td>@endif
                                            @if(in_array('estimated_date', $search_params))<td>@if($item->estimated_date){{$item->estimated_date}}@endif</td>@endif
                                            @if(in_array('agency', $search_params))<td>{{$item->agency}}</td>@endif
                                            @if(in_array('company', $search_params))<td>{{$item->company}}</td>@endif
                                            @if(in_array('dock', $search_params))<td>{{$item->dock}}</td>@endif
                                            <td>
                                                @php
                                                    $item_products = json_decode($item->product_list, true);
                                                    $product_count = count($item_products);
                                                @endphp
                                                <button type="button" class="btn btn-sm btn-block btn-secondary" data-toggle="popover" data-html="true" data-placement="bottom" title="{{__('page.product_list')}}" 
                                                    data-content="<ul class='font-weight-bold'>
                                                            @foreach ($item_products as $key => $value)
                                                                @php
                                                                    $product = \App\Models\Product::find($key);
                                                                    if(isset($footer_product_array[$key])){
                                                                        $footer_product_array[$key]+=$value;
                                                                    }else{
                                                                        // echo $key;
                                                                        // array_merge($footer_product_array, array($key => $value));
                                                                        $footer_product_array[$key] =$value;
                                                                    }
                                                                @endphp
                                                                <li>{{$product->name}} ({{$product->code}}) : {{number_format($value, 2)}}</li>
                                                            @endforeach
                                                        </ul>">
                                                    {{$product_count}} {{__('page.products')}}
                                                </button>
                                            </td>                                     
                                            <td>{{number_format($item->total_container, 2)}}</td>
                                            <td>{{number_format($item->peso_carga, 2)}}</td>
                                            <td>{{number_format($item->tara, 2)}}</td>
                                            <td>{{number_format($item->vgm, 2)}}</td>
                                            <td>                                                
                                                <a href="{{route('container.detail', $item->id)}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> {{__('page.detail')}}</a>
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
                                        'week_c' => $week_c, 
                                        'week_d' => $week_d, 
                                        'search_params' => $search_params,
                                    ])->links() !!}
                                </div>
                            </div>
                            @dump($footer_product_array)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){            
            $("#pagesize").change(function(){
                $("#pagesize_form").submit();
            });
        })
    </script>
@endsection
