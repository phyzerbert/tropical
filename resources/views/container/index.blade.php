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
            <div class="block-header block-header-default">
                <form action="" class="form-inline mr-auto">
                    @csrf
                    <input type="text" class="form-control form-control-sm mt-2" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </form>
                <a href="{{route('container.create')}}" class="btn btn-success btn-sm float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-5">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PRO-FORMA INVOICE NE</th>
                                <th>IDENTIFICACION O NIT</th>
                                <th>SEMANA</th>
                                <th>CONTENEDOR</th>
                                <th>PRECINTO</th>
                                <th>TEMPERATURA</th>
                                <th>DAMPER</th>
                                <th>BOOKING</th>
                                <th>PUERTO DE DESTINO</th>
                                <th>FECHA</th>
                                <th>EMBARCADERO</th>
                                <th>TIPO DE MERCANCIA</th>
                                <th>AGENCIA ADUANERA</th>
                                <th>EMPRESA O PERSONA NATURAL</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td>@isset($item->proforma->reference_no){{$item->proforma->reference_no}}@endisset</td>
                                    <td>{{$item->identification_or_nit}}</td>
                                    <td>{{$item->semana}}</td>
                                    <td>{{$item->contenedor}}</td>
                                    <td>{{$item->precinto}}</td>
                                    <td>{{$item->temperatura}} °C</td>
                                    <td>{{$item->damper}}</td>
                                    <td>{{$item->booking}}</td>
                                    <td>{{$item->port_of_discharge}}</td>
                                    <td>{{$item->fetcha}}</td>
                                    <td>{{$item->embarcadero}}</td>
                                    <td>{{$item->tipo_de_mercancia}}</td>
                                    <td>{{$item->agencia_aduanera}}</td>
                                    <td>{{$item->company_or_person}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                <a class="dropdown-item" href="{{route('container.detail', $item->id)}}">{{__('page.detail')}}</a>
                                                <a class="dropdown-item product_list" href="javascript:void(0)" data-value='{{$item->product_list}}''>{{__('page.product')}}</a>
                                                <a class="dropdown-item" href="{{route('container.edit', $item->id)}}">{{__('page.edit')}}</a>
                                                <a class="dropdown-item" href="{{route('container.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
                                            </div>
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
                            {!! $data->appends(['keyword' => $keyword])->links() !!}
                        </div>
                    </div>
                </div>                 
            </div>
        </div>
    </div>

    <div class="modal fade" id="productModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.product')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body p-3">
                    <table class="table table-bordered w-100" id="productTable">
                        <thead>
                            <tr>
                                <th>{{__('page.product_code')}}</th>
                                <th>{{__('page.description')}}</th>
                                <th>{{__('page.quantity')}}</th>
                            </tr>
                            <tbody></tbody>
                        </thead>
                    </table>                        
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{__('page.close')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $(".product_list").click(function(){
                let product_list = $(this).data('value')
                $('#productTable tbody').html('')
                for (const id in product_list) {
                    if (product_list.hasOwnProperty(id)) {
                        const element = product_list[id];
                        Dashmix.loader('show')
                        $.ajax({
                            url: '/get_product',
                            type: "POST",
                            data: {id:id},
                            dataType: 'json',
                            success: function(data){
                                $('#productTable tbody').append(`
                                    <tr>
                                        <td>${data.code}</td>
                                        <td>${data.description}</td>
                                        <td>${element}</td>
                                    </tr>
                                `)
                                Dashmix.loader('hide')
                            }
                        })
                    }
                }
                $("#productModal").modal();
            });
        })
    </script>
@endsection
