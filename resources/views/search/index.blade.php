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
                <div class="d-md-none push">
                    <button type="button" class="btn btn-block btn-hero-primary" data-toggle="class-toggle" data-target="#side-content" data-class="d-none">
                        {{__('page.search_menu')}}
                    </button>
                </div>
                <div id="side-content" class="d-none d-md-block push">
                    <div class="block block-fx-pop">
                        <div class="block-content py-5">
                            <form action="" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="search_week_c">{{__('page.week_c')}}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="search_week_c" name="week_c" value="{{$week_c}}" placeholder="{{__('page.week_c')}}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="search_week_c">{{__('page.week_d')}}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="search_week_d" name="week_d" value="{{$week_d}}" placeholder="{{__('page.week_d')}}" />
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label class="d-block">{{__('page.identification_or_not')}}</label>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_identification_yes" name="identification" value="yes" @if($identification == 'yes') checked @endif />
                                        <label class="custom-control-label" for="search_identification_yes">{{__('page.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_identification_no" name="identification" value="no" @if($identification == 'no') checked @endif />
                                        <label class="custom-control-label" for="search_identification_no">{{__('page.no')}}</label>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label class="d-block">{{__('page.container')}}</label>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_container_yes" name="container" value="yes" @if($container == 'yes') checked @endif />
                                        <label class="custom-control-label" for="search_container_yes">{{__('page.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_container_no" name="container" value="no" @if($container == 'no') checked @endif />
                                        <label class="custom-control-label" for="search_container_no">{{__('page.no')}}</label>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label class="d-block">{{__('page.booking')}}</label>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_booking_yes" name="booking" value="yes" @if($booking == 'yes') checked @endif />
                                        <label class="custom-control-label" for="search_booking_yes">{{__('page.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_booking_no" name="booking" value="no" @if($booking == 'no') checked @endif />
                                        <label class="custom-control-label" for="search_booking_no">{{__('page.no')}}</label>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label class="d-block">BL</label>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_bl_yes" name="bl" value="yes" @if($bl == 'yes') checked @endif />
                                        <label class="custom-control-label" for="search_bl_yes">{{__('page.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input" id="search_bl_no" name="bl" value="no" @if($bl == 'no') checked @endif />
                                        <label class="custom-control-label" for="search_bl_no">{{__('page.no')}}</label>
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
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
                                        <th>WEEK C</th>
                                        <th>WEEK D</th>
                                        @if($container == 'yes')<th>Container</th>@endif
                                        @if($booking == 'yes')<th>Booking</th>@endif
                                        @if($bl == 'yes')<th>BL</th>@endif
                                        <th>{{__('page.shipping_company')}}</th>
                                        <th>{{__('page.fruit_loading_date')}}</th>
                                        <th>{{__('page.ship_departure_date')}}</th>
                                        <th>{{__('page.estimated_date_of_shipping_company')}}</th>
                                        <th>{{__('page.agency')}}</th>
                                        <th>{{__('page.company')}}</th>
                                        <th>{{__('page.dock')}}</th>
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
                                            <td>{{$item->shipping_company}}</td>
                                            <td>@if($item->fruit_loading_date){{$item->fruit_loading_date}}@endif</td>
                                            <td>@if($item->ship_departure_date){{$item->ship_departure_date}}@endif</td>
                                            <td>@if($item->estimated_date){{$item->estimated_date}}@endif</td>
                                            <td>{{$item->agency}}</td>
                                            <td>{{$item->company}}</td>
                                            <td>{{$item->dock}}</td>
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
                                    {!! $data->appends(['week_c' => $week_c, 'week_d' => $week_d])->links() !!}
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
