<!DOCTYPE html>
<html>
<head>
    <title>{{__('page.customer')}} {{__('page.proforma')}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans">
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <style>
        body {
            color: #584747;
            border: solid 1px black;
            padding: 10px;
            background: url('{{asset("images/bg_pdf.jpg")}}') no-repeat;
            background-size: 100% 100%;
        }
        .table td, .table th {
            padding: .4rem;
        }
        .main {
        }
        .title {
            margin-top: 30px;
            text-align:center;
            font-size:30px;
            font-weight: 700;
            text-decoration:underline;
        }
        .value {
            font-size: 14px;
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
            font-size: 14px;
            font-weight: 600;
        }
        #table-item {
            font-size: 14px;
            color: #584747;
        }
        .footer {
            position: absolute;
            bottom: 25px;;
        }
        .footer p {
            font-size: 11px;
            color: #584747;
        }
    </style>
</head>
<body>
    <div class="main">
        @php
            $grand_total = $sale->grand_total;
            $paid = $sale->payments->sum('amount');
        @endphp
        <br /><br /><br /><br /><br />
        <table class="w-100 mt-5" id="table-customer">
            <tbody>
                <tr>
                    <td class="w-50" valign="top">
                        <h5 class="mb-0">{{__('page.proforma')}}</h5>
                        <p class="my-0 text-center" style="font-size:24px">{{$sale->reference_no}}</p>
                        <p class="mt-2 mb-0">{{__('page.date')}} : <span class="value">{{date('d/m/Y', strtotime($sale->date))}}</span></p>
                        <p class="my-0">{{__('page.concerning_week')}} : <span class="value">{{ $sale->concerning_week }}</span></p>
                    </td>
                    <td class="w-50" valign="top">
                        <p class="my-0">{{__('page.name')}} : <span class="value">{{$sale->customer->name}}</p>
                        <p class="my-0">{{__('page.company')}} : <span class="value">{{$sale->customer->company}}</span></p>
                        <p class="my-0">{{__('page.email')}} : <span class="value">{{$sale->customer->email}}</span></p>
                        <p class="my-0">{{__('page.phone_number')}} : <span class="value">{{$sale->customer->phone_number}}</span></p>
                        <p class="my-0">{{__('page.city')}} : <span class="value">{{$sale->customer->city}}</span></p>
                        <p class="my-0">{{__('page.address')}} : <span class="value">{{$sale->customer->address}}</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3 class="mt-2" style="font-size: 22px; font-weight: 600;">{{__('page.items')}}</h3>
        <table class="table" id="table-item">
            <thead class="table-primary">
                <tr class="bg-blue">
                    <th style="width:25px;">#</th>
                    <th>{{__('page.product_name_code')}}</th>
                    <th>{{__('page.quantity')}}</th>
                    <th>{{__('page.price')}}</th>
                    <th>{{__('page.amount')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $footer_quantity = $footer_amount = 0;
                    $item_data = $sale->items;
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
                        <td class="price"> {{number_format($item->price, 2)}} </td>
                        <td class="amount"> {{ number_format($item->total_amount, 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">{{__('page.total')}}</th>
                    <th>{{number_format($footer_quantity)}}</th>
                    <th></th>
                    <th>{{number_format($footer_amount, 2)}}</th>  
                </tr>
            </tfoot>
        </table>
        <div class="mt-4">
            <h4 class="text-right pr-3">
                {{__('page.grand_total')}} : <span class="text-primary">{{number_format($sale->total_to_pay, 2)}}</span> 
            </h4>
        </div>
        <div class="footer text-center w-100">
            <p class="my-0">TROPICAL GIDA VE GENEL TIC LTD.</p>
            <p class="my-0">DIRECCIÓN / ADDRESS: MERKEZ MAHALLESI,SECKIN SOKAK NO:3 BUMERANG OFIS DAIRE NO, 73,74</p>
            <p class="my-0">CODIGO POSTAL / POSTAL CODE 34406</p>
            <p class="my-0">CIUDAD / CITY KAGITHANE / ISTAMBUL</p>
            <p class="my-0">PAIS / COUNTRY TURKEY</p>
            <p class="my-0">N.I.F / V.A.T NUM. 8590988403</p>
            <p class="my-0">TELÉFONO / PHONE NUMBER +90(212 2940 331) +90(212 2490 332)</p>
        </div>
    </div>
</body>
</html>