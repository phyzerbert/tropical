<!DOCTYPE html>
<html>
<head>
    <title>{{__('page.customer')}} {{__('page.report')}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans">
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <style>
        body {
            border: solid 1px black;
            padding: 10px;
        }
        .title {
            margin-top: 30px;
            text-align:center;
            font-size:30px;
            font-weight: 800;
            text-decoration:underline;
        }
        .value {
            font-size: 18px;
            font-weight: 500;
            text-decoration: underline;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #2d2d2d;
        }
        .table thead th {
            border-bottom: 2px solid #2d2d2d;
        }
        #table-customer {
            font-size: 18px;
            font-weight: 600;
            color: black;
        }
        #table-customer tbody td {
            height: 50px;
        }
        .footer-link {
            position: absolute;
            right:20px;
            bottom: 10px;
        }
    </style>
</head>
<body>
    <h1 class="title">{{__('page.customer')}} {{__('page.report')}}</h1>

    @php
        $sales_array = $customer->sales()->pluck('id');
        $total_sales = $customer->sales()->count();
        $total_to_pay = $customer->sales()->sum('total_to_pay');
        $paid = \App\Models\Payment::whereIn('sale_id', $sales_array)->sum('amount');
    @endphp

    <table class="w-100 mt-5" id="table-customer">
        <tbody>
            <tr>
                <td class="w-50">{{__('page.name')}} : <span class="value">{{$customer->name}}</span></td>
                <td class="w-50">{{__('page.company')}} : <span class="value">{{$customer->company}}</span></td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.email')}} : <span class="value">{{$customer->email}}</span></td>
                <td class="w-50">{{__('page.phone_number')}} : <span class="value">{{$customer->phone_number}}</td>
            </tr>
            <tr>
                <td colspan="2" class="w-50">{{__('page.address')}} : <span class="value">{{$customer->address}}</td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.city')}} : <span class="value">{{$customer->city}}</span></td>
                <td class="w-50">{{__('page.total_amount')}} : <span class="value">{{number_format($total_to_pay, 2)}}</span></td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.paid')}} : <span class="value">{{number_format($paid, 2)}}</span></td>
                <td class="w-50">{{__('page.balance')}} : <span class="value">{{number_format($total_to_pay - $paid, 2)}}</span></td>
            </tr>
        </tbody>
    </table>
    <h3 class="mt-5" style="font-size: 24px; font-weight: 500;">{{__('page.sales')}}</h3>
    <table class="table">
        <thead class="table-primary">
            <tr class="bg-blue">
                <th style="width:25px;">#</th>
                <th>{{__('page.sale')}}</th>
                <th>{{__('page.total_to_pay')}}</th>
                <th>{{__('page.paid')}}</th>
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
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="reference_no">{{$item->reference_no}}</td>
                    <td class="total_to_pay"> {{number_format($total_to_pay, 2)}} </td>
                    <td class="paid"> {{ number_format($paid, 2) }} </td>
                    <td class="balance" data-value="{{$total_to_pay - $paid, 2}}"> {{number_format($total_to_pay - $paid, 2)}} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">{{__('page.total')}}</th>
                <th>{{number_format($footer_total_to_pay, 2)}}</th>
                <th>{{number_format($footer_paid, 2)}}</th>
                <th>{{number_format($footer_total_to_pay - $footer_paid, 2)}}</th>  
            </tr>
        </tfoot>
    </table>
    <h3 class="footer-link"><a href="http://tropicalgida.com" target="_blank">{{config('app.name')}}</a></h3>
</body>
</html>