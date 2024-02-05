<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        ul {
            list-style-type: none !important;
            list-style: none !important;
        }

        li {
            list-style: none !important;
        }

        .bold {
            font-weight: bold !important;
        }

        .child {
            width: 50% !important;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="text-align:left !important;">
                <img src="{{ $bol_logo }}" width="80px" alt="">
            </td>
            <td style="text-align: center !important;">
                <h3 class="bold" style="text-align: center !important;">{{ $order->api_id }}</h2>
            </td>
        </tr>
    </table>
    @foreach ($order->getOrderItems() as $orderItem)
        @php
            $product = \App\Models\Product::whereEan($orderItem['product']['ean'])->first();
            $product_image = $product != null && $product->image != null ? Storage::url('products/' . $product->id . '/' . $product->image) : asset('assets/img/product-placeholder.webp');
        @endphp
        <table>
            <tr style="border-bottom:1px solid #000 !important;">
                <td><img src="{{ $product_image }}" alt="" width="200"></td>
                <td style="border-left:1px solid #000;">
                    <ul style="list-style-type: none !important;">
                        <li>
                            {{ $orderItem['product']['title'] }}
                        </li>
                    </ul>
                </td>
            </tr>

            <tr style="padding-top:2% !important;">
                <td style="border-top:1px solid #000;">
                    <span class="bold">EAN:</span> {{ $orderItem['product']['ean'] }}
                </td>
                <td style="border-left:1px solid #000;border-top:1px solid #000; ">
                    <span class="bold">Aanta: </span> <span class="bold"
                        style="fot-size:18px !importantl">{{ $orderItem['quantity'] }}</span>
                </td>
            </tr>
        </table>
        <hr style="color:#000;">
    @endforeach


    @php
        $shipment_details = $order->getShipmentDetails();
    @endphp


    <div style="margin-top:5%; width:100% !important;">
        <table>
            <tr>
                <td style="text-align: left !important;">
                    <div>
                        {{ $shipment_details['firstName'] }} &nbsp; {{ @$shipment_details['surname'] }}
                    </div>
                    <div>
                        {{ @$shipment_details['streetName'] }} &nbsp;
                        {{ @$shipment_details['houseNumber'] }}
                    </div>
                    <div>
                        {{ @$shipment_details['zipCode'] }} &nbsp; {{ @$shipment_details['city'] }}
                    </div>
                    <div>
                        {{ @$shipment_details['countryCode'] }}
                    </div>
                    <br> <br>
                    <div>
                        <span class="bold">Verzenddatum:
                        </span>{{ \Carbon\Carbon::parse($order->place_date)->format('Y-M-d') }}
                    </div>
                </td>
                <td style="text-align: right !important;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img
                        src="{{ $borvat_logo }}" width="120px" style="padding-left:5% !important;" alt="">
                </td>
            </tr>
        </table>


    </div>

</body>

</html>
