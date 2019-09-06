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
                <h3 class="float-left mb-0">PRO-FORMA NE INVOICE : <ins class="text-primary">{{$invoice->reference_no}}</ins></h3>
                <h3 class="float-left mb-0 ml-3 mr-auto">SEMANA : <ins class="text-primary">{{$invoice->concerning_week}}</ins></h3>
                <a href="{{route('container.create')}}" class="btn btn-success btn-sm float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive px-1">
                    @php
                        $products = $invoice->items->pluck('product_id')->toArray();
                        $footer_product_total = array();
                    @endphp
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">PRO-FORMA INVOICE NE</th>
                                <th rowspan="2">SEMANA</th>
                                <th rowspan="2">IDENTIFICACION O NIT</th>
                                <th rowspan="2">CONTENEDOR</th>
                                <th rowspan="2">PRECINTO</th>
                                <th rowspan="2">TEMPERATURA</th>
                                <th rowspan="2">DAMPER</th>
                                <th rowspan="2">BOOKING</th>
                                <th rowspan="2">PUERTO DE DESTINO</th>
                                <th rowspan="2">FECHA</th>
                                <th rowspan="2">EMBARCADERO</th>
                                <th rowspan="2">TIPO DE MERCANCIA</th>
                                <th rowspan="2">AGENCIA ADUANERA</th>
                                <th rowspan="2">EMPRESA O PERSONA NATURAL</th>
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
                                        $footer_total_container = $footer_peso_carga = $footer_tara = $footer_vgm = 0;
                                    @endphp
                                    <th>{{\App\Models\Product::find($id)->description}}</th>
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
                                    <td>{{$item->proforma->reference_no}}</td>
                                    <td>{{$item->semana}}</td>
                                    <td>{{$item->identification_or_nit}}</td>
                                    <td>{{$item->contenedor}}</td>
                                    <td>{{$item->precinto}}</td>
                                    <td>{{$item->temperatura}} °C</td>
                                    <td>{{$item->damper}}</td>
                                    <td>{{$item->booking}}</td>
                                    <td>{{$item->port_of_discharge}}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->fetcha)) }}</td>
                                    <td>{{$item->embarcadero}}</td>
                                    <td>{{$item->tipo_de_mercancia}}</td>
                                    <td>{{$item->agencia_aduanera}}</td>
                                    <td>{{$item->company_or_person}}</td>
                                    @foreach ($products as $id)
                                        @php
                                            $footer_product_total[$id] += $container_products[$id];
                                        @endphp
                                        <td>{{number_format($container_products[$id])}}</td>
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
                                <th colspan="14" class="text-right">TOTAL </th>
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
