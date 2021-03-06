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
        .field {
            text-transform: uppercase;
            font-size: 12px;
        }
        td.value {
            line-height: 1;
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
            bottom: 10px;;
        }
        .footer tr td {
            font-size: 11px;
            color: #584747;
            text-align: center;
            line-height: 1;
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
                    <td class="w-50" valign="top">
                        <h5 class="mb-0 text-uppercase">{{__('page.proforma')}}</h5>
                        <p class="my-0 text-center" style="font-size:24px">{{$sale_proforma->reference_no}}</p>
                        
                    </td>
                    <td class="w-50 pt-3 text-right" rowspan="2" valign="top">
                        <table class="w-100">
                            <tr><td class="value">{{$sale_proforma->customer->name}}</td></tr>
                            <tr><td class="value">{{$sale_proforma->customer->company}}</td></tr>
                            <tr><td class="value">{{$sale_proforma->customer->email}}</td></tr>
                            <tr><td class="value">{{$sale_proforma->customer->phone_number}}</td></tr>
                            <tr><td class="value">{{$sale_proforma->customer->city}}</td></tr>
                            <tr><td class="value">{{$sale_proforma->customer->address}}</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="pt-1">
                        <table class="w-100">
                            <tr>
                                <td class="field">{{__('page.date')}} : </td>    
                                <td class="value">{{date('d/m/Y', strtotime($sale_proforma->date))}}</td>
                            </tr>
                            <tr>
                                <td class="field">{{__('page.concerning_week')}} : </td>
                                <td class="value">{{ $sale_proforma->concerning_week }}</td>
                            </tr>
                        </table>
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
                {{__('page.grand_total')}} : <span class="text-primary">{{number_format($sale_proforma->total_to_pay, 2)}}</span> 
            </h4>
        </div>
        <div class="footer text-center">
            <table class="w-100">
                <tr><td>TROPICAL GIDA VE GENEL TIC LTD.</td></tr>
                <tr><td>DIRECCIÓN / ADDRESS: MERKEZ MAHALLESI,SECKIN SOKAK NO:3 BUMERANG OFIS DAIRE NO, 73,74</td></tr>
                <tr><td>CODIGO POSTAL / POSTAL CODE 34406</td></tr>
                <tr><td>CIUDAD / CITY KAGITHANE / ISTAMBUL</td></tr>
                <tr><td>PAIS / COUNTRY TURKEY</td></tr>
                <tr><td>N.I.F / V.A.T NUM. 8590988403</td></tr>
                <tr><td>TELÉFONO / PHONE NUMBER +90(212 2940 331) +90(212 2490 332)</td></tr>
            </table>
        </div>
    </div>
</body>
</html>