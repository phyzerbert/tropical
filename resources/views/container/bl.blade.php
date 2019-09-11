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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-magnifier"></i> BL</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item">{{__('page.container_load')}}</li>
                        <li class="breadcrumb-item active" aria-current="page">BL</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                @include('elements.pagesize')
                <form action="" class="form-inline float-left">
                    @csrf
                    <input type="text" class="form-control form-control-sm col-md-5 mt-2" id="search_bl" name="bl" value="{{$bl}}" placeholder="BL">
                    <button type="submit" class="btn btn-sm btn-primary ml-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                    <a href="javascript:;" class="btn btn-danger btn-sm mt-2 ml-2" id="btn-reset"><i class="fa fa-eraser"></i> {{__('page.reset')}}</a>
                </form>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-5">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Container</th>
                                <th>Ship Departure Date</th>
                                <th>Estimated Date Of Shipping Company</th>
                                <th>WEEK C</th>
                                <th>WEEK D</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$item->container}}</td>
                                    <td>{{$item->ship_departure_date}}</td>
                                    <td>{{$item->estimated_date}}</td>
                                    <td>{{$item->week_c}}</td>
                                    <td>{{$item->week_d}}</td>
                                </tr>
                            @endforeach                            
                        </tbody>
                    </table>                    
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {!! $data->appends(['bl' => $bl])->links() !!}
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
                $("#search_bl").val('');
            });

            $("#pagesize").change(function(){
                $("#pagesize_form").submit();
            });
        })
    </script>
@endsection
