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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-magnifier"></i> BOOKING</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item">{{__('page.container_load')}}</li>
                        <li class="breadcrumb-item active" aria-current="page">BOOKING</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <form action="" method="post" class="col-md-12 form-inline">
                    @csrf
                    <label for="pagesize" class="control-label mt-2">{{__('page.show')}} :</label>
                    <select class="form-control form-control-sm mx-md-2 mt-2" name="pagesize" id="pagesize">
                        <option value="15" @if($pagesize == '15') selected @endif>15</option>
                        <option value="50" @if($pagesize == '50') selected @endif>50</option>
                        <option value="100" @if($pagesize == '100') selected @endif>100</option>
                        <option value="200" @if($pagesize == '200') selected @endif>200</option>
                        <option value="500" @if($pagesize == '500') selected @endif>500</option>
                        <option value="" @if($pagesize == '100000') selected @endif>All</option>
                    </select>
                    <input type="text" class="form-control form-control-sm col-md-5 mt-2" id="search_booking" name="booking" value="{{$booking}}" placeholder="BOOKING">
                    <button type="submit" class="btn btn-sm btn-primary ml-md-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <a href="javascript:;" class="btn btn-danger btn-sm mt-2 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</a>
                </form>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-5">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>BL</th>
                                <th>{{__('page.container')}}</th>
                                <th>{{__('page.ship_departure_date')}}</th>
                                <th>{{__('page.estimated_date_of_shipping_company')}}</th>
                                <th>{{__('page.week_c')}}</th>
                                <th>{{__('page.week_d')}}</th>
                                <th width="100">{{__('page.detail')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$item->bl}}</td>
                                    <td>{{$item->container}}</td>
                                    <td>{{$item->ship_departure_date}}</td>
                                    <td>{{$item->estimated_date}}</td>
                                    <td>{{$item->week_c}}</td>
                                    <td>{{$item->week_d}}</td>                                    
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
                            {!! $data->appends(['booking' => $booking])->links() !!}
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
            $("#btn-reset").click(function(){
                $("#search_booking").val('');
            });

            $("#pagesize").change(function(){
                $("#pagesize_form").submit();
            });
        })
    </script>
@endsection
