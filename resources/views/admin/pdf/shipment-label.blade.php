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
            font-size: 14px !important,
        }

        .borderd-table tr:first-of-type td {
            border: none !important;
        }

        .borderd-table tr {
            height: 50px !important;
        }

        body {
            font-family: sans-serif, serif !important;
            border: 2px solid black !important;
            padding: 5px !important;
        }
    </style>
</head>

<body>

    <h3 class="text-right">{{ $shipment->order?->api_id }}</h3>
    {{-- Shipment --}}
    <img src="{{ public_path('storage/' . $shipment->account->logo) }}" width="80px" height="80px" alt="logo" style="margin-top: -50px;">
    <h5 style="margin-top:5% !important;">Verzenddatum:</h3>
        <div>
            <div class="child">
                <ul>
                    <li class="red">
                        Bol.com nr:
                        {{ $shipment->order?->api_id }}
                    </li>
                    <li>
                        Afzender:
                    </li>
                    <li>
                        Borvat.com
                    </li>
                    <li>
                        Overwelving 2
                    </li>
                    <li>
                        7201LT Zutphen
                    </li>
                </ul>

            </div>

            <div style="display: inline-block;" style="padding-left:20% !important;">
                @if (@$shipment->country_code != 'NL')
                <img src="{{ $outside_netherlands_logo }}" alt="" width="50%">
                @else
                    <img src="{{ $inside_netherlands_logo }}" alt="" width="70%">
                @endif
            </div>
        </div>



        <div class="parent" style="margin-top: -50px;">
            <div class="child no-border">
                <img src="" alt="" width="40%">
                <br>
                <p class="red">{{ 'BS' . $shipment->api_id }}</p>
            </div>

            <br>
            <div class="child" style="padding:15px !important;"
                style="border:1px solid #000;margin-bottom:15% !important;margin-left:5% !important;">
                <ul>
                    <li>
                        {{ @$shipment->first_name . ' ' . @$shipment->surname }}
                    </li>
                    <li>
                        {{ @$shipment->street_name . ' ' . @$shipment->house_no . ' ' . @$shipment->shipment_details['houseNumberExtension'] }}
                    </li>
                    <li>
                        {{ @$shipment->zip_code }} &nbsp;
                        {{ @$shipment->city }}
                    </li>
                    <li>
                        {{ @$shipment->country_code }}
                    </li>
                </ul>
            </div>

        </div>
        </div>


</body>

</html>
