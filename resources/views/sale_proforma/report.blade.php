<!DOCTYPE html>
<html>
<head>
    <title>{{__('page.customer')}} {{__('page.proforma')}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans">
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <style>
        body {
            border: solid 1px black;
            padding: 10px;
            background: url('{{asset("images/bg_pdf.jpg")}}') no-repeat;
            background-size: 100% 100%;
        }
        .main {
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
    <div class="main">
        @php
            $grand_total = $sale_proforma->grand_total;
            $paid = $sale_proforma->payments->sum('amount');
        @endphp
        <br /><br /><br /><br /><br />
        <table class="w-100 mt-5" id="table-customer">
            <tbody>
                <tr>
                    <td class="w-50">{{__('page.date')}} : <span class="value">{{$sale_proforma->timestamp}}</span></td>
                    <td class="w-50">{{__('page.reference_no')}} : <span class="value">{{$sale_proforma->reference_no}}</span></td>
                </tr>
            </tbody>
        </table>
        <h3 class="mt-2 mb-1" style="font-size: 24px; font-weight: 500;">• {{__('page.customer')}}</h3>
        <table class="w-100" id="table-customer">
            <tbody>
                <tr>
                    <td class="w-50">{{__('page.company')}} : <span class="value">{{$sale_proforma->customer->company}}</span></td>
                    <td class="w-50">{{__('page.name')}} : <span class="value">{{$sale_proforma->customer->name}}</span></td>
                </tr>
                <tr>
                    <td class="w-50">{{__('page.email')}} : <span class="value">{{$sale_proforma->customer->email}}</span></td>
                    <td class="w-50">{{__('page.phone_number')}} : <span class="value">{{$sale_proforma->customer->phone_number}}</span></td>
                </tr>
                <tr>
                    <td class="w-50">{{__('page.city')}} : <span class="value">{{$sale_proforma->customer->city}}</span></td>
                    <td class="w-50">{{__('page.address')}} : <span class="value">{{$sale_proforma->customer->address}}</span></td>
                </tr>
            </tbody>
        </table>
        <h3 class="mt-3" style="font-size: 24px; font-weight: 500;">• {{__('page.items')}}</h3>
        <table class="table">
            <thead class="table-primary">
                <tr class="bg-blue">
                    <th style="width:25px;">#</th>
                    <th>{{__('page.product_name_code')}}</th>
                    <th>{{__('page.quantity')}}</th>
                    <th>{{__('page.amount')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $footer_quantity = $footer_amount = 0;
                    $item_data = $sale_proforma->items;
                @endphp
                @foreach ($item_data as $item)
                    @php
                        $footer_quantity += $item->quantity;
                        $footer_amount += $item->total_amount;
                    @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="product_name_code">{{$item->product->name}} ( {{$item->product->code}} )</td>
                        <td class="quantity"> {{number_format($item->quantity)}} </td>
                        <td class="amount"> {{ number_format($item->total_amount, 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">{{__('page.total')}}</th>
                    <th>{{number_format($footer_quantity)}}</th>
                    <th>{{number_format($footer_amount, 2)}}</th>  
                </tr>
            </tfoot>
        </table>
        <div class="mt-4">
            <h4 class="text-right">
                {{__('page.grand_total')}} : <span class="text-primary">{{number_format($grand_total, 2)}}</span> 
                {{__('page.paid')}} : <span class="text-primary">{{number_format($paid, 2)}}</span>
                {{__('page.balance')}} : <span class="text-primary">{{number_format($grand_total - $paid, 2)}}</span>
            </h4>
        </div>
    </div>
</body>
</html>