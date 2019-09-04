@extends('layouts.master')

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
            <div class="block-header block-header-default justify-content-end">
                <a href="{{route('container.create')}}" class="btn btn-success btn-sm float-right" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-vcenter">
                        <thead class="thead-light">
                            <tr>
                                <td>#</td>
                                <td>IDENTIFICACION O NIT</td>
                                <td>PRECINTO</td>
                                <td>CONTENEDOR</td>
                                <td>TEMPERATURA</td>
                                <td>DAMPER</td>
                                <td>BOOKING</td>
                                <td>PUERTO DE DESTINO</td>
                                <td>FECHA</td>
                                <td>EMBARCADERO</td>
                                <td>TIPO DE MERCANCIA</td>
                                <td>AGENCIA ADUANERA</td>
                                <td>EMPRESA O PERSONA NATURAL</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$item->proforma->reference_no}}</td>
                                    <td>{{$item->contenedor}}</td>
                                    <td>{{$item->precinto}}</td>
                                    <td>{{$item->temperatura}}</td>
                                    <td>{{$item->damper}}</td>
                                    <td>{{$item->booking}}</td>
                                    <td>{{$item->port_of_discharge}}</td>
                                    <td>{{$item->fetcha}}</td>
                                    <td>{{$item->embarcadero}}</td>
                                    <td>{{$item->tipo_de_mercancia}}</td>
                                    <td>{{$item->agencia_aduanera}}</td>
                                    <td>{{$item->company_or_person}}</td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>                 
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.container_load')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="create_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                        
                    </div>    
                    <div class="modal-footer">
                        <button type="submit" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check"></i>&nbsp;{{__('page.save')}}</button>
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
                $("#addModal").modal();
            });
        })
    </script>
@endsection
