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
                        <div class="table-responsive pt-4 pb-8">
                            <table class="table table-bordered table-vcenter">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PRO-FORMA INVOICE NE</th>
                                        @if($identification == 'yes')<th>IDENTIFICACION O NIT</th>@endif
                                        <th>{{__('page.week_c')}}</th>
                                        <th>{{__('page.week_d')}}</th>
                                        @if($container == 'yes')<th>{{__('page.container')}}</th>@endif
                                        @if($booking == 'yes')<th>{{__('page.booking')}}</th>@endif
                                        @if($bl == 'yes')<th>BL</th>@endif
                                        @if($shipping_company == 'yes')<th>{{__('page.shipping_company')}}</th>@endif
                                        @if($temperature == 'yes')<th>{{__('page.temperature')}}</th>@endif
                                        @if($damper == 'yes')<th>{{__('page.damper')}}</th>@endif
                                        @if($type_of_merchandise == 'yes')<th>{{__('page.type_of_merchandise')}}</th>@endif
                                        @if($fruit_loading_date == 'yes')<th>{{__('page.fruit_loading_date')}}</th>@endif
                                        @if($ship_departure_date == 'yes')<th>{{__('page.ship_departure_date')}}</th>@endif
                                        @if($estimated_date == 'yes')<th>{{__('page.estimated_date_of_shipping_company')}}</th>@endif
                                        @if($agency == 'yes')<th>{{__('page.agency')}}</th>@endif
                                        @if($company == 'yes')<th>{{__('page.company')}}</th>@endif
                                        @if($dock == 'yes')<th>{{__('page.dock')}}</th>@endif
                                        <th>{{__('page.detail')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td>@isset($item->proforma->reference_no){{$item->proforma->reference_no}}@endisset</td>
                                            @if($identification == 'yes')<td>{{$item->identification_or_nit}}</td>@endif
                                            <td>{{$item->week_c}}</td>
                                            <td>{{$item->week_d}}</td>
                                            @if($container == 'yes')<td>{{$item->container}}</td>@endif
                                            @if($booking == 'yes')<td>{{$item->booking}}</td>@endif
                                            @if($bl == 'yes')<td>{{$item->bl}}</td>@endif
                                            @if($shipping_company == 'yes')<td>{{$item->shipping_company}}</td>@endif
                                            @if($temperature == 'yes')<td>{{$item->temperatura}} °C</td>@endif
                                            @if($damper == 'yes')<td>{{$item->damper}}</td>@endif
                                            @if($type_of_merchandise == 'yes')<td>{{$item->type_of_merchandise}}</td>@endif
                                            @if($fruit_loading_date == 'yes')<td>@if($item->fruit_loading_date){{$item->fruit_loading_date}}@endif</td>@endif
                                            @if($ship_departure_date == 'yes')<td>@if($item->ship_departure_date){{$item->ship_departure_date}}@endif</td>@endif
                                            @if($estimated_date == 'yes')<td>@if($item->estimated_date){{$item->estimated_date}}@endif</td>@endif
                                            @if($agency == 'yes')<td>{{$item->agency}}</td>@endif
                                            @if($company == 'yes')<td>{{$item->company}}</td>@endif
                                            @if($dock == 'yes')<td>{{$item->dock}}</td>@endif
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
                                        'identification' => $identification,
                                        'container' => $container,
                                        'booking' => $booking,
                                        'bl' => $bl,
                                        'shipping_company' => $shipping_company,
                                        'temperature' => $temperature,
                                        'damper' => $damper,
                                        'type_of_merchandise' => $type_of_merchandise,
                                        'fruit_loading_date' => $fruit_loading_date,
                                        'ship_departure_date' => $ship_departure_date,
                                        'estimated_date' => $estimated_date,
                                        'agency' => $agency,
                                        'company' => $company,
                                        'dock' => $dock,
                                    ])->links() !!}
                                </div>
                            </div>
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
            
        })
    </script>
@endsection
