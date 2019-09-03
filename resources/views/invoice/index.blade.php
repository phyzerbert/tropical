@extends('layouts.master')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="nav-main-link-icon si si-present"></i> {{__('page.invoice_management')}}</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('page.invoice_management')}}</li>
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
                    <input type="text" class="form-control form-control-sm" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </form>                
                <a href="{{route('invoice.create')}}" class="btn btn-success btn-sm" id="btn-add"><i class="fa fa-plus"></i> {{__('page.add_new')}}</a>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-hover">
                    <thead class="thead-colored thead-primary">
                        <tr class="bg-blue">
                            <th style="width:50px;">#</th>
                            <th>{{__('page.reference_no')}}</th>
                            <th>{{__('page.issue_date')}}</th>
                            <th>{{__('page.due_date')}}</th>
                            <th>{{__('page.customers_vat')}}</th>
                            <th>{{__('page.delivery_date')}}</th>
                            <th style="width:120px;">{{__('page.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>                                
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                <td class="reference_no">{{$item->reference_no}}</td>
                                <td class="issue_date">{{$item->issue_date}}</td>
                                <td class="due_date">{{$item->due_date}}</td>
                                <td class="customers_vat">{{$item->customers_vat}}</td>
                                <td class="delivery_date">{{$item->delivery_date}}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}</button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                            <a class="dropdown-item" href="javascript:void(0)">{{__('page.detail')}}</a>
                                            <a class="dropdown-item" href="javascript:void(0)">{{__('page.add_payment')}}</a>
                                            <a class="dropdown-item" href="javascript:void(0)">{{__('page.payment_list')}}</a>
                                            <a class="dropdown-item" href="{{route('invoice.edit', $item->id)}}">{{__('page.edit')}}</a>
                                            <a class="dropdown-item" href="{{route('invoice.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
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


@endsection

@section('script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
