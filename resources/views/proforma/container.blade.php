@extends('layouts.master')
@section('style')
    <style>
    td, th {
        white-space: nowrap;
    }
    .table-responsive table tr th:last-child,
    .table-responsive table tr td:last-child {
        border-right-width: 2px;
    }
    </style>
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-cloud-upload"></i> {{__('page.container_load')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.container_load')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="clearfix block-header-default p-3">
                <h3 class="float-left mb-0 mr-auto">PRO-FORMA NE INVOICE : <ins class="text-primary">{{$invoice->reference_no}}</ins></h3>                
                <form action="" class="form-inline float-right" method="post" id="keyword_filter_form">
                    @csrf    
                    <input type="text" name="keyword" id="keyword_filter" value="{{$keyword}}" class="form-control form-control-sm" placeholder="Keyword" />
                    <a href="{{route('container.create')}}" class="btn btn-success btn-sm float-right ml-2" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
                </form>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive px-1">
                    @php
                        if($invoice->shipment){
                            $products = $invoice->shipment->items->pluck('product_id')->toArray();
                        }else{                            
                            $products = $invoice->items->pluck('product_id')->toArray();
                        }
                        $footer_product_total = array();
                        $footer_total_container = $footer_peso_carga = $footer_tara = $footer_vgm = 0;
                    @endphp
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">PRO-FORMA INVOICE NE</th>
                                <th rowspan="2">IDENTIFICACION O NIT</th>
                                <th rowspan="2">{{__('page.week_c')}}</th>
                                <th rowspan="2">{{__('page.week_d')}}</th>
                                <th rowspan="2">{{__('page.container')}}</th>
                                <th rowspan="2">{{__('page.booking')}}</th>
                                <th rowspan="2">BL</th>
                                <th rowspan="2">{{__('page.shipping_company')}}</th>
                                <th rowspan="2">{{__('page.fruit_loading_date')}}</th>
                                <th rowspan="2">{{__('page.ship_departure_date')}}</th>
                                <th rowspan="2">{{__('page.estimated_date_of_shipping_company')}}</th>
                                <th rowspan="2">{{__('page.agency')}}</th>
                                <th rowspan="2">{{__('page.company')}}</th>
                                <th rowspan="2">{{__('page.dock')}}</th>
                                @foreach ($products as $id)
                                    <th>{{\App\Models\Product::find($id)->code}}</th>
                                @endforeach                                
                                <th rowspan="2">TOTAL CONTENEDOR</th>
                                <th rowspan="2">PESO CARGA</th>
                                <th rowspan="2">TARA</th>
                                <th rowspan="2">VGM</th>
                            </tr>
                            <tr>
                                @foreach ($products as $id)
                                    @php
                                        $footer_product_total[$id] = 0;
                                        
                                    @endphp
                                    <th>{{\App\Models\Product::find($id)->name}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                @php
                                    $container_products = json_decode($item->product_list, 'true');
                                    $footer_total_container += $item->total_container;
                                    $footer_peso_carga += $item->peso_carga;
                                    $footer_tara += $item->tara;
                                    $footer_vgm += $item->vgm;
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>@isset($item->proforma->reference_no){{$item->proforma->reference_no}}@endisset</td>
                                    <td>{{$item->identification_or_nit}}</td>
                                    <td>{{$item->week_c}}</td>
                                    <td>{{$item->week_d}}</td>
                                    <td>{{$item->container}}</td>
                                    <td>{{$item->booking}}</td>
                                    <td>{{$item->bl}}</td>
                                    <td>{{$item->shipping_company}}</td>
                                    <td>{{$item->fruit_loading_date}}</td>
                                    <td>{{$item->ship_departure_date}}</td>
                                    <td>{{$item->estimated_date}}</td>
                                    <td>{{$item->agency}}</td>
                                    <td>{{$item->company}}</td>
                                    <td>{{$item->dock}}</td>
                                    @foreach ($products as $id)
                                        @php
                                            $footer_product_total[$id] += $container_products[$id];
                                        @endphp
                                        <td>
                                            @isset($container_products[$id]){{number_format($container_products[$id])}}@endisset
                                        </td>
                                    @endforeach                                    
                                    <td>{{number_format($item->total_container)}}</td>
                                    <td>{{number_format($item->peso_carga)}}</td>
                                    <td>{{number_format($item->tara)}}</td>
                                    <td>{{number_format($item->vgm)}}</td>
                                </tr>
                            @endforeach                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="15" class="text-right">TOTAL </th>
                                @foreach ($products as $id)
                                    <th>{{number_format($footer_product_total[$id])}}</th>
                                @endforeach
                                <th>{{number_format($footer_total_container)}}</th>
                                <th>{{number_format($footer_peso_carga)}}</th>
                                <th>{{number_format($footer_tara)}}</th>
                                <th>{{number_format($footer_vgm)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>                 
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
    </script>
@endsection
