<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('page.customers_report')}}</title>
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <style>
        body {
            border: solid 1px black;
            padding: 10px;
            background-color: #FFF;
        }
        .header-title {
            margin-top: 25px;
            text-align:center;
            font-size:30px;
            font-weight: 800;
            text-decoration:underline;
            clear: both;
        }
        .value {
            text-decoration: underline;
            font-weight: 600;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #2d2d2d;
        }
        .table thead th {
            border-bottom: 2px solid #2d2d2d;
        }
        #table-sales td {
            padding-top: 8px ;
            padding-bottom: 3px ;
        }
        #table-customer {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }
        #table-customer tbody td {
            height: 25px;
        }
        .table-payment td,
        .table-payment th {
            padding: 5px;
            border: none;
            font-size: 12px;
        }
        .main-color {
            color: #495057;
        }
        .logo {
            position: absolute;
            left: 20px;
            top: 20px;
        }
    </style>
</head>
<body>
    <img src="{{asset('images/logo.png')}}" class="logo" alt="">
    <h5 class="float-right main-color">{{__('page.date')}} : {{date('d/m/Y')}}</h5>
    <h1 class="header-title main-color">{{__('page.customers_report')}}</h1>

    @php
        $sales_array = $customer->sales()->pluck('id');
        $total_sales = $customer->sales()->count();
        $total_amount = $customer->sales()->sum('total_to_pay');
        $paid = \App\Models\Payment::whereIn('sale_id', $sales_array)->sum('amount');
    @endphp

    <table class="w-100 mt-3 main-color" id="table-customer">
        <tbody>
            <tr>
                <td>
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td>{{__('page.name')}} : </td>
                                <td class="value">{{$customer->name}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.company')}} : </td>
                                <td class="value">{{$customer->company}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.email')}} : </td>
                                <td class="value">{{$customer->email}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.phone_number')}} : </td>
                                <td class="value">{{$customer->phone_number}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.city')}} : </td>
                                <td class="value">{{$customer->city}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.address')}} : </td>
                                <td class="value">{{$customer->address}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="bottom">
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td>{{__('page.total_amount')}} : </td>
                                <td class="value" style="font-size:20px">{{number_format($total_amount)}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.paid')}} : </td>
                                <td class="value" style="font-size:20px">{{number_format($paid)}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.balance')}} : </td>
                                <td class="value" style="font-size:20px">{{number_format($total_amount - $paid)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <h3 class="mt-4" style="font-size: 18px; font-weight: 500;">{{__('page.sale')}}</h3>
    <table class="table" id="table-sales">
        <thead>
            <tr class="bg-blue">
                <th>
                    {{__('page.date')}}
                </th>
                <th>{{__('page.reference_no')}}</th>
                <th>{{__('page.total_to_pay')}}</th>
                <th>{{__('page.payment')}}</th>
                <th>{{__('page.balance')}}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $footer_total_to_pay = $footer_paid = 0;
                $data = $customer->sales;
            @endphp
            @foreach ($data as $item)
                @php
                    $paid = $item->payments()->sum('amount');
                    $total_to_pay = $item->total_to_pay;
                    $footer_total_to_pay += $total_to_pay;
                    $footer_paid += $paid;
                @endphp
                <tr>
                    <td class="date">{{$item->date}}</td>
                    <td class="reference_no">
                        {{$item->reference_no}}<br /><span class="text-info">{{$item->week_c}}</span>
                    </td>
                    <td class="total_to_pay"> {{number_format($total_to_pay)}} </td>
                    <td class="payment">                        
                        @php
                            $payments = $item->payments;
                        @endphp
                        <table class="table-payment w-100">
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{date('d/m/Y', strtotime($payment->timestamp))}}</td>
                                        <td>{{$payment->reference_no}}</td>
                                        <td width="80">{{number_format($payment->amount)}}</td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td>{{__('page.no_payment')}}</td>
                                     </tr>                    
                                @endforelse
                            </tbody>
                            @if($payments->isNotEmpty())
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">{{__('page.total')}}</th>
                                        <th>{{ number_format($paid) }} </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </td>
                    <td class="balance"> {{number_format($total_to_pay - $paid)}} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">{{__('page.total')}}</th>
                <th>{{number_format($footer_total_to_pay)}}</th>
                <th>{{number_format($footer_paid)}}</th>
                <th>{{number_format($footer_total_to_pay - $footer_paid)}}</th>  
            </tr>
        </tfoot>
    </table>
</body>
</html>