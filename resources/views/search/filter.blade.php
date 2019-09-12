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
                <div class="form-group mt-3">
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
                <div class="form-group mt-3">
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
                <div class="form-group mt-3">
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
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.shipping_company')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_shipping_company_yes" name="shipping_company" value="yes" @if($shipping_company == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_shipping_company_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_shipping_company_no" name="shipping_company" value="no" @if($shipping_company == 'no') checked @endif />
                        <label class="custom-control-label" for="search_shipping_company_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.temperature')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_temperature_yes" name="temperature" value="yes" @if($temperature == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_temperature_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_temperature_no" name="temperature" value="no" @if($temperature == 'no') checked @endif />
                        <label class="custom-control-label" for="search_temperature_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.damper')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_damper_yes" name="damper" value="yes" @if($damper == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_damper_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_damper_no" name="damper" value="no" @if($damper == 'no') checked @endif />
                        <label class="custom-control-label" for="search_damper_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.type_of_merchandise')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_type_of_merchandise_yes" name="type_of_merchandise" value="yes" @if($type_of_merchandise == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_type_of_merchandise_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_type_of_merchandise_no" name="type_of_merchandise" value="no" @if($type_of_merchandise == 'no') checked @endif />
                        <label class="custom-control-label" for="search_type_of_merchandise_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.fruit_loading_date')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_fruit_loading_date_yes" name="fruit_loading_date" value="yes" @if($fruit_loading_date == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_fruit_loading_date_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_fruit_loading_date_no" name="fruit_loading_date" value="no" @if($fruit_loading_date == 'no') checked @endif />
                        <label class="custom-control-label" for="search_fruit_loading_date_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.ship_departure_date')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_ship_departure_date_yes" name="ship_departure_date" value="yes" @if($ship_departure_date == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_ship_departure_date_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_ship_departure_date_no" name="ship_departure_date" value="no" @if($ship_departure_date == 'no') checked @endif />
                        <label class="custom-control-label" for="search_ship_departure_date_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.estimated_date_of_shipping_company')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_estimated_date_yes" name="estimated_date" value="yes" @if($estimated_date == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_estimated_date_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_estimated_date_no" name="estimated_date" value="no" @if($estimated_date == 'no') checked @endif />
                        <label class="custom-control-label" for="search_estimated_date_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.agency')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_agency_yes" name="agency" value="yes" @if($agency == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_agency_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_agency_no" name="agency" value="no" @if($agency == 'no') checked @endif />
                        <label class="custom-control-label" for="search_agency_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.company')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_company_yes" name="company" value="yes" @if($company == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_company_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_company_no" name="company" value="no" @if($company == 'no') checked @endif />
                        <label class="custom-control-label" for="search_company_no">{{__('page.no')}}</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="d-block">{{__('page.dock')}}</label>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_dock_yes" name="dock" value="yes" @if($dock == 'yes') checked @endif />
                        <label class="custom-control-label" for="search_dock_yes">{{__('page.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        <input type="radio" class="custom-control-input" id="search_dock_no" name="dock" value="no" @if($dock == 'no') checked @endif />
                        <label class="custom-control-label" for="search_dock_no">{{__('page.no')}}</label>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </div>
            </form>

        </div>
    </div>
</div>