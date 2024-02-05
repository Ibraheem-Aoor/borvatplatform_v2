<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        ul {
            list-style-type: none;
        }

        div.child {
            float: left;
            width: 40%;
        }

        div.child>div {
            margin-right: 10px;
            height: 100px;
            box-sizing: border-box;
            /* background: yellow; */
            text-align: center;
            border: 1px solid red;
        }

        .no-border {
            border: none;
            margin-top: 5% !important;
        }

        img {
            /* margin-top: 5% !important; */
        }

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

        .red {
            color: red !important;
        }

        .full-width {
            width: 100% !important;
            height: 50% !important;
        }

        .mb-2 {
            margin-bottom: 2% !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-danger {
            color: red !important;
        }

        .borderd-table tr:first-of-type td {
            border: none !important;
        }
    </style>
</head>

<body>

    {{-- order --}}

    @php
        $order_items = $shippment->getOrderItems();
        $shipment_details = $shippment->getAddress();
        $product_image_width = 200;
        if (count($order_items) > 1) {
            $product_image_width = 150;
        }
    @endphp
    {{-- Order No One Time --}}
    <table class="full-width" border="1">
        <tr>
            <td class="text-center">
                <img src="{{ $borvat_logo }}" width="150px" alt="">
            </td>
            <td class="text-center">
                <h3 class="bold" style="text-align: right !important;">{{ $shippment->code }}</h2>
            </td>
        </tr>
    </table>
    {{-- End Order No --}}
    @foreach ($order_items as $order_item)
        @php
            $product = \App\Models\Product::whereEan($order_item['ean_code'])->first();
            $product_image = $product != null && $product->image != null ? Storage::url('products/' . $product->id . '/' . $product->image) : asset('assets/img/product-placeholder.webp');
        @endphp
        <table class="full-width borderd-table" border="1">
            <tr>
                <td class="text-left"><img src="{{ $product_image }}" alt="" width="{{ $product_image_width }}">
                </td>
                <td class="text-right" colspan="2">
                    <ul style="list-style-type: none !important;">
                        <li class="mb-2">
                            {{ $order_item['product_name'] }}
                        </li>
                        <li class="mb-2">
                            <span class="bold">EAN:</span>{{ $order_item['ean_code'] }}
                        </li>
                        <li>
                            <span class="bold text-danger">Anatal</span>: {{ $order_item['qty'] }}
                        </li>
                    </ul>
                </td>
            </tr>
            <tr class="tr-bordered">
                @isset($product->content)
                    <td>
                        {{ $product->content }}
                    </td>
                @endisset
                <td class="text-danger" class="text-center">
                    @if ($product?->note)
                        {{ $product?->note }}
                    @else
                        &nbsp;
                    @endif
                </td>
                <td class="text-center">
                    {{ $product?->number_of_pieces * $order_item['qty'] }}
                </td>
                <td class="text-center">
                    <img src="{{ $borvat_logo }}" width="120">
                </td>
            </tr>
        @endisset
    </table>
@endforeach







<div style="margin-top:5%; width:100% !important;">
    <table>
        <tr>
            <td style="text-align: left !important;">
                <div>
                    {{ @$shipment_details['name'] }}
                </div>
                <div>
                    {{ @$shipment_details['address'] }} &nbsp;
                    {{ @$shipment_details['house_no'] }}
                </div>
                <div>
                    {{ @$shipment_details['zip_code'] }} &nbsp; {{ $shipment_details['city'] }}
                </div>
                <div>
                    {{ @$shipment_details['country'] }}
                </div>
                <div>
                    <span>Verzenddatum:
                        {{ $today_date ??  $shippment->place_date }}
                    </span>
                </div>
            </td>
            {{-- <td style="text-align: right !important;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img
                        src="{{ $borvat_logo }}" width="120px" style="padding-left:5% !important;" alt="">
                </td> --}}
        </tr>
    </table>

</div>



<br>

{{-- Shipment --}}

<h3>Verzenddatum:</h3>
<div style="margin-top:5% !important;border:2px solid #000;" style="width: 100%;">
    <div class="child">
        @include('admin.partials.sender-details', [
            'shippment' => $shippment,
            'shipment_sender_details' => $shipment_sender_details,
        ])
    </div>

    <div style="display: inline-block;" style="padding-left:30% !important;">
        @if (@$shipment_details['country'] != 'NL')
            <img src="{{ asset('assets/img/outside_netherlands_logo.jpg') }}" alt="" width="300">
        @else
            <img src="{{ asset('assets/img/inside_netherlands_logo.jpg') }}" alt="" width="70%">
        @endif
    </div>
</div>
<br>

<div class="parent">
    <div class="child no-border">
        <img src="{{ asset('assets/img/logo.png') }}" alt="" width="40%">
        <br>
        <p class="red">{{ 'BS' . $shippment->code }}</p>
    </div>

    <div class="child" style="padding:15px !important;"
        style="border:1px solid #000;margin-bottom:15% !important;margin-left:5% !important;">
        <ul>
            <li>
                {{ @$shipment_details['name'] }}
            </li>
            <li>
                {{ @$shipment_details['address'] . ' ' . @$shipment_details['houseNumber'] }}
            </li>
            <li>
                {{ @$shipment_details['zip_code'] }} &nbsp;
                {{ @$shipment_details['city'] }}
            </li>
            <li>
                {{ @$shipment_details['country'] }}
            </li>
        </ul>
    </div>

</div>
</div>


</body>

</html>
