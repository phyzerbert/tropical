<div class="d-md-none push">
    <button type="button" class="btn btn-block btn-hero-primary" data-toggle="class-toggle" data-target="#side-content" data-class="d-none">
        {{__('page.search_menu')}}
    </button>
</div>
<div id="side-content" class="d-none d-md-block push">
    <div class="block block-fx-pop">
        <div class="block-content py-5">
            <div class="border border-primary rounded p-3">
            <form action="" method="post">
                @csrf
                <div class="form-group row mt-3">
                    <label class="col-lg-4 col-form-label" for="search_week_c">{{__('page.week_c')}}</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="search_week_c" name="week_c" value="{{$week_c}}" placeholder="{{__('page.week_c')}}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label" for="search_week_c">{{__('page.week_d')}}</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control form-control-sm" id="search_week_d" name="week_d" value="{{$week_d}}" placeholder="{{__('page.week_d')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_identification" name="search_params[]" value="identification" @if(in_array('identification', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_identification">{{__('page.identification_or_not')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_container" name="search_params[]" value="container" @if(in_array('container', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_container">{{__('page.container')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_booking" name="search_params[]" value="booking" @if(in_array('booking', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_booking">{{__('page.booking')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_bl" name="search_params[]" value="bl" @if(in_array('bl', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_bl">BL</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_shipping_company" name="search_params[]" value="shipping_company" @if(in_array('shipping_company', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_shipping_company">{{__('page.shipping_company')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_temperature" name="search_params[]" value="temperature" @if(in_array('temperature', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_temperature">{{__('page.temperature')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_damper" name="search_params[]" value="damper" @if(in_array('damper', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_damper">{{__('page.damper')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_type_of_merchandise" name="search_params[]" value="type_of_merchandise" @if(in_array('type_of_merchandise', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_type_of_merchandise">{{__('page.type_of_merchandise')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_fruit_loading_date" name="search_params[]" value="fruit_loading_date" @if(in_array('fruit_loading_date', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_fruit_loading_date">{{__('page.fruit_loading_date')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_ship_departure_date" name="search_params[]" value="ship_departure_date" @if(in_array('ship_departure_date', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_ship_departure_date">{{__('page.ship_departure_date')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_estimated_date" name="search_params[]" value="estimated_date" @if(in_array('estimated_date', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_estimated_date">{{__('page.estimated_date_of_shipping_company')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_agency" name="search_params[]" value="agency" @if(in_array('agency', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_agency">{{__('page.agency')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_company" name="search_params[]" value="company" @if(in_array('company', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_company">{{__('page.company')}}</label>
                    </div>
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="search_dock" name="search_params[]" value="dock" @if(in_array('dock', $search_params)) checked @endif)>
                        <label class="custom-control-label" for="search_dock">{{__('page.dock')}}</label>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </div>
            </form>
        </div>

        </div>
    </div>
</div>