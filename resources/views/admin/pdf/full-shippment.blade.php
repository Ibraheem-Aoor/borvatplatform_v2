<!DOCTYPE html>
<html lang="en">

@php
    $product_image_width = 200;
    $table_font_size = '13px !important';
    if ($shipment->products()->count() > 1) {
        $product_image_width = 140;
        $table_font_size = '10px !important';
    }
@endphp

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

        body {
            font-size: 12px !important;
        }

        .full-width {
            width: 100% !important;
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

        .borderd-table {
            font-size: {{ $table_font_size }},
        }

        .borderd-table tr:first-of-type td {
            border: none !important;
        }

        .borderd-table tr {
            height: 50px !important;
        }

        body {
            font-family: sans-serif, serif !important;
        }

        .fs-30 {
            font-size: 50px !important;
        }
    </style>

</head>

<body>

    {{-- order --}}

    {{-- Order No One Time --}}
    <table class="full-width" border="1">
        <tr>
            <td class="text-center">
                <img src="{{ $bol_logo }}" width="80px" alt="">
            </td>
            <td class="text-center">
                <h2 class="text-danger">{{ $page_count }}</h2> <br>
                <h2 class="bold" style="text-align: right !important;">{{ $shipment->order->api_id }}</h2>
            </td>
        </tr>
    </table>
    {{-- End Order No --}}
    @foreach ($products as $product)
        @php
            $product_image = getImageUrl('storage/products/' . $product->id . '/' . $product->image);
        @endphp
        <table class="full-width borderd-table" border="1">
            <tr>
                <td class="text-left"><img src="{{ $product_image }}" alt="" width="{{ $product_image_width }}">
                </td>
                <td class="text-right" colspan="2">
                    <ul style="list-style-type: none !important;">

                        <li class="mb-2">
                            {{ $product->title }}
                        </li>
                        <li class="mb-2">
                            <span class="bold">EAN:</span>{{ $product->ean }}
                        </li>
                        <li>
                            <span class="bold text-danger">Anatal</span>: {{ $product->pivot->quantity }}
                        </li>
                    </ul>
                </td>
            </tr>
            <tr class="tr-bordered">
                @isset($product->content)
                    <td class="text-center bold text-danger">
                        {{ $product->content }}
                    </td>
                @endisset
                <td class="text-center bold text-danger" dir>
                    @if ($product?->note)
                        {{ $product?->note }}
                    @else
                        &nbsp;
                    @endif
                </td>
                <td class="text-center text-danger bold fs-30">
                    <h3>
                        {{ $product?->number_of_pieces * $product->pivot->quantity }}
                    </h3>
                </td>
                <td class="text-center">
                    <img src="{{ getImageUrl($shipment->account->logo) }}" width="90">
                </td>
            </tr>
        </table>
    @endforeach

    <div>
        <table>
            <tr>
                <td style="text-align: left !important;">
                    <div>
                        {{ $shipment->firstName }} &nbsp; {{ $shipment->surname }}
                    </div>
                    <div>
                        {{ $shipment->street_name }} &nbsp;
                        {{ $shipment->house_number }}
                    </div>
                    <div>
                        {{ $shipment->zip_code }} &nbsp; {{ $shipment->city }}
                    </div>
                    <div>
                        {{ $shipment->country_code }}
                    </div>
                    <div>
                        <span>Verzenddatum:
                            {{ $today_date ?? $shipment->order?->place_date }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>




    <br>




</body>

</html>
