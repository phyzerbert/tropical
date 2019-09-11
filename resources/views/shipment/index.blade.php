@extends('layouts.master')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2"><i class="far fa-file-alt"></i> Shipment</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="nav-main-link-icon si si-home"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Shipment</li>
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
                    <input type="text" class="form-control form-control-sm mt-2" style="width: 200px;" name="keyword" value="{{$keyword}}" placeholder="{{__('page.search')}}...">
                    <button type="submit" class="btn btn-sm btn-primary ml-2 mt-2"><i class="fa fa-search"></i> {{__('page.search')}}</button>
                </form>                
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive pb-7">                    
                    <table class="table table-bordered table-hover">
                        <thead class="thead-colored thead-primary">
                            <tr class="bg-blue">
                                <th style="width:50px;">#</th>
                                <th>{{__('page.proforma')}}</th>
                                <th>{{__('page.date')}}</th>
                                <th>{{__('page.week_c')}}</th>
                                <th>{{__('page.status')}}</th>
                                <th>{{__('page.total_amount')}}</th>
                                <th style="width:120px;">{{__('page.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>                              
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                    <td class="reference_no">{{$item->reference_no}}</td>
                                    <td class="date">@if($item->proforma){{ date('d/m/Y', strtotime($item->proforma->date)) }}@endif</td>
                                    <td class="week_c">{{ $item->week_c }}</td>
                                    <td class="status">
                                        @if($item->is_received == 1)
                                            <span class="badge badge-success">{{__('page.received')}}</span>
                                        @else
                                            <span class="badge badge-warning">{{__('page.pending')}}</span>
                                        @endif
                                    </td>
                                    <td class="total_to_pay">{{number_format($item->total_to_pay, 2)}}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('page.action')}}&nbsp;</button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-align-primary">
                                                {{-- <a class="dropdown-item" href="{{route('shipment.detail', $item->id)}}">{{__('page.detail')}}</a> --}}
                                                @if (!$item->is_received)                                                    
                                                    <a class="dropdown-item" href="{{route('shipment.receive', $item->id)}}" data-id="{{$item->id}}">{{__('page.receive')}}</a>
                                                @endif
                                                <a class="dropdown-item" href="{{route('shipment.delete', $item->id)}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')">{{__('page.delete')}}</a>
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
    </div>


@endsection

@section('script')
    <script>
        $(document).ready(function(){            
            // $(".btn-add-payment").click(function(){
            //     let id = $(this).data('id');
            //     let balance = $(this).parents('tr').find('.balance').data('value');
            //     $("#payment_form .proforma_id").val(id);
            //     $("#payment_form .amount").val(balance);
            //     $("#paymentModal").modal();
            // });
        })
    </script>
@endsection
