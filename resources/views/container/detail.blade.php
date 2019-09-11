@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('master/js/plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('master/js/plugins/select2/css/select2.min.css')}}">  
    <link rel="stylesheet" href="{{asset('master/js/plugins/imageviewer/css/jquery.verySimpleImageViewer.css')}}">
    <style>
        #image_preview {
            max-width: 600px;
            height: 600px;
        }
        .image_viewer_inner_container {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> {{__('page.container_detail')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.container_detail')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @php
        $proforma = \App\Models\Proforma::find($container->proforma_id);
    @endphp
    <div class="content content-boxed">
        <div class="block block-fx-shadow">
            <div class="block-content">
                <div class="p-sm-4 p-xl-6">
                    <div class="row">
                        <div class="col-12">
                            <h4>PRO-FORMA INVOICE NE : @if($proforma->reference_no){{$proforma->reference_no}}@endif</h4>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->identification_or_nit}}</p>
                                    <p class="text-muted font-weight-bold mb-0">IDENTIFICACION O NIT</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->week_c}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.week_c')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->week_d}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.week_d')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->container}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.container')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->booking}}</p>
                                    <p class="text-muted font-weight-bold mb-0">BOOKING</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->bl}}</p>
                                    <p class="text-muted font-weight-bold mb-0">BL</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->shipping_company}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.shipping_company')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->temperatura}} °C</p>
                                    <p class="text-muted font-weight-bold mb-0">TEMPERATURA</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->damper}}</p>
                                    <p class="text-muted font-weight-bold mb-0">DAMPER</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->type_of_merchandise}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.type_of_merchandise')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->fruit_loading_date}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.fruit_loading_date')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->ship_departure_date}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.ship_departure_date')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->estimated_date}}</p>
                                    <p class="text-muted font-weight-bold mb-0">Estimated Date</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->agency}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.agency')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->company}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.company')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded block-bordered block-link-pop">
                                <div class="block-content block-content-default p-3 text-center">
                                    <p class="font-size-h3 font-w300 mb-0">{{$container->dock}}</p>
                                    <p class="text-muted font-weight-bold mb-0">{{__('page.dock')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered table-striped table-vcenter">
                                <thead class="table-success">
                                    <tr>
                                        <th></th>
                                        <th>{{__('page.product_code')}}</th>
                                        <th>{{__('page.product_name')}}</th>
                                        <th>{{__('page.quantity')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $product_list = json_decode($container->product_list, true);
                                    @endphp
                                    @foreach ($product_list as $key => $value)
                                        @php
                                            $product = \App\Models\Product::find($key);
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <img class="img-avatar img-avatar48" src="@if($product->image){{asset($product->image)}}@else{{asset('images/no-image.jpg')}}@endif" alt="">
                                            </td>
                                            <td>
                                                {{$product->code}}
                                            </td>
                                            <td>
                                                {{$product->name}}
                                            </td>
                                            <td>{{number_format($value)}}</td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix mt-3">
                        <a href="{{route('container.index')}}" class="btn btn-primary float-right"><i class="far fa-file-alt"></i> {{__('page.container_load')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachModal">
        <div class="modal-dialog" style="margin-top:17vh">
            <div class="modal-content">
                <div id="image_preview"></div>
            </div>
        </div>
    </div>

@endsection


@section('script')
<script src="{{asset('master/js/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
<script>
        $(document).ready(function(){
            $(".attachment").click(function(e){
                e.preventDefault();
                let path = $(this).data('value');
                $("#image_preview").html('')
                $("#image_preview").verySimpleImageViewer({
                    imageSource: path,
                    frame: ['100%', '100%'],
                    maxZoom: '900%',
                    zoomFactor: '10%',
                    mouse: true,
                    keyboard: true,
                    toolbar: true,
                });
                $("#attachModal").modal();
            });
        })
    </script>
@endsection