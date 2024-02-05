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
    </style>
</head>

<body>

    <div>
        @include('admin.partials.sender-details' , ['shippment' => $shippment , 'shipment_sender_details' => $shipment_sender_details])

    </div>


    @php
        $shippment_details = $shippment->getAddress();
    @endphp
    <div class="parent">

        <div class="child no-border">
            <img src="{{ asset('assets/img/logo.png') }}" alt="" width="40%">
        </div>


        <div class="child" style="padding:5px !important;" style="border:1px solid #000;">
            <ul>
                <li>
                    {{ @$shippment_details['name']}}
                </li>
                <li>
                    {{ @$shippment_details['company'] }}
                </li>
                <li>
                    {{ @$shippment_details['zip_code'] }} &nbsp; {{ @$shippment_details['city'] }}
                </li>
                <li>
                    {{ @$$shippment_details['country'] }}
                </li>
            </ul>
        </div>

    </div>

</body>

</html>
