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
            <div class="block-content block-content-full">                
                <form action="{{route('container.save')}}" method="POST" enctype="multipart/form-data" id="app">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">{{__('page.proforma_invoice')}}</label>
                                <div class="input-group">                                  
                                    <select class="form-control select2" name="proforma" id="search_proforma" v-model="proforma_id" @change="getItems()" required data-placeholder="PRO-FORMA NE INVOICE">
                                        <option label="{{__('page.proforma_invoice')}}" hidden></option>
                                        @foreach ($proformas as $item)
                                            <option value="{{$item->id}}" @if(old('proforma') == $item->id) selected @endif>{{$item->reference_no}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                @error('proforma')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">IDENTIFICATION O NIT</label>
                                <input class="form-control" type="text" name="identification_or_nit" placeholder="IDENTIFICATION O NIT">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">CONTENEDOR</label>
                                <input class="form-control" type="text" name="contenedor" placeholder="CONTENEDOR">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">PRECINTO</label>
                                <input class="form-control" type="text" name="precinto" placeholder="PRECINTO">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">TEMPERATURA</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" step="0.1" name="temperatura" placeholder="TEMPERATURA" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
        
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">DAMPER</label>
                                <input class="form-control" type="text" name="damper" placeholder="DAMPER">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">BOOKING</label>
                                <input class="form-control" type="text" name="booking" placeholder="BOOKING">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">PUERTO DE DESTINO</label>
                                <input class="form-control" type="text" name="port_of_discharge" v-model="port_of_discharge" placeholder="PUERTO DE DESTINO">
                            </div>
                        </div>
                        <div class="form-group col-md-4 mt-3">
                            <label class="form-control-label">{{__('page.date')}} </label>
                            <input class="datepicker form-control" type="text" name="fetcha" v-model="fetcha" placeholder="{{__('page.date')}}" autocomplete="off">
                            @error('fetcha')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">EMBARCADERO</label>
                                <input class="form-control" type="text" name="embarcadero" placeholder="EMBARCADERO">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">TIPO DE MERCANCIA</label>
                                <input class="form-control" type="text" name="tipo_de_mercancia" placeholder="TIPO DE MERCANCIA">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">AGENCIA ADUANERA</label>
                                <input class="form-control" type="text" name="agencia_aduanera" placeholder="AGENCIA ADUANERA">
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="form-group">
                                <label class="form-control-label">EMPRESA O PERSONA NATURA</label>
                                <input class="form-control" type="text" name="company_or_person" placeholder="EMPRESA O PERSONA NATURA">
                            </div>
                        </div>
                    </div>
                    <h4>FRUTA</h4>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-colored" id="item_table">
                                    <thead class="table-success">
                                        <tr>
                                            <th>{{__('page.product_code')}}</th>
                                            <th>{{__('page.description')}}</th>
                                            <th>{{__('page.quantity')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="top-search-form">
                                        <tr v-for="(item,i) in items" :key="i">
                                            <td>
                                                <input type="hidden" name="product_id[]" class="product_id" :value="item.product_id" />
                                                @{{item.product_code}}
                                            </td>
                                            <td>
                                                @{{item.description}}
                                            </td>
                                            <td class="quantity">
                                                <input type="text" class="form-control form-control-sm" name="quantity[]" v-model="item.quantity" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">TOTAL CONTENEDOR</label>
                                <input class="form-control" type="number" name="total_container" v-model="total_container" placeholder="TOTAL CONTENEDOR" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">PESO CARGA</label>
                                <input class="form-control" type="number" name="peso_carga" step="0.01" v-model="peso_carga" placeholder="PESO CARGA" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">TARA</label>
                                <input class="form-control" type="number" name="tara" step="0.01" v-model="tara" placeholder="TARA" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">VGM</label>
                                <input class="form-control" type="number" name="vgm" step="0.01" v-model="vgm" placeholder="VGM" >
                            </div>
                        </div>
                    </div>
                    <div class="form-layout-footer mt-3 text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> {{__('page.save')}}</button>
                        <a href="{{route('container.index')}}" class="btn btn-warning ml-2"><i class="fa fa-times mg-r-2"></i> {{__('page.cancel')}}</a>
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

            // $('.select2').each(function() {
            //     $(this).select2({ width: 'resolve' });                    
            // });
        })
    </script>
    <script src="{{asset('master/js/custom/create_container.js')}}"></script>
@endsection
