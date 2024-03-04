<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <title>Document</title>
    <style>
        * {
            margin-left: 0;
        }

        body {
            font-family: 'Rubik', sans-serif;
            font-size: 10px;
            margin: auto !important;
        }


        .textdiv {
            border: 1px solid black;
            width: 150%;
            background-color: rgb(248, 248, 248);
            border-radius: 11px;
            padding: 7px 17px;
        }

        .textdiv h1 {
            text-align: center !important;
            font-size: 25px;
            letter-spacing: 20px;
            margin: 0;
        }

        .fristDiv {
            width: 60%;
        }

        .secondDiv {
            margin-left: 10px;
            margin-top: 10px;
        }

        .fristDiv2 {
            width: 29%;
            margin-top: -403px;
        }

        .fristsq {
            width: 115%;
            height: 80px;
            border: 2px solid black;
            background-image: url("{{ public_path('assets/img/bg_1.png') }}");
            background-size: cover;
            margin-bottom: 10px;
            border-radius: 20px;
        }

        .fristsq h1 {
            text-align: center !important;
            line-height: 50px;
            color: #3c3c3c;
            font-weight: bold;
            /* font-family:'Arial'; */
        }

        .ozDiv {
            width: 88%;
            border: 2px solid black;
            border-radius: 20px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .smallDivINSecond {
            height: 45px;
            background-color: rgb(111, 111, 111);
            margin-bottom: 15px;
            border-radius: 65px;
            border: 2px solid black;
            box-shadow: 3px 10px 15px black;
        }

        .smallDivINSecond h2 {
            color: white;
            font-size: 16px;
            text-align: center !important;
            font-weight: bold;
            text-shadow: 3px 3px 3px black;
        }

        .cePhoto {
            width: 95%;
            height: 64px;
            background-color: white;
            border-radius: 10px;
            border: 2px solid black;
            padding: 3px 7px;
        }

        .cePhoto img {
            width: 80%;
            height: 100%;
        }

        .secondDiv2 {
            height: 400px;
            background-image: url("{{ public_path('assets/img/bg_3.png') }}");
            background-size: cover !important;
            width: 310px;
            margin-top: 70px;
            margin-left: -170px;
            border-radius: 30px;
            border: 1px solid black;
            position: relative;
            border-bottom: none !important;
        }

        .secondDiv2 img {
            width: 200px !important;
            height: 200px !important;
            margin: auto !important;
            margin-top: 30px !important;
            border-radius: 20px;
        }

        .intodiv {
            background-color: rgb(0, 0, 0);
            margin: auto !important;
            margin-bottom: 5px;
            width: 85%;
            border-radius: 20px;
        }

        .intodiv p {
            color: white;
            text-align: center !important;
            font-size: larger !important;
            padding: 5px !important;
        }

        .thierdDiv {
            height: 55px;
            background-image: url("{{ public_path('assets/img/bg_4.png') }}");
            background-size: cover !important;
            width: 100%;
            position: absolute !important;
            bottom: 0 !important;
            border: 1px solid black;
            border-radius: 10px;
        }

        .thierdDiv p {
            text-align: center !important;
            line-height: 0px;
            letter-spacing: 2px;
            font-size: x-large !important;
            text-align: center !important;
            padding-top: 10px !important;
        }




        .oneDiv {
            background-image: url("{{ public_path('assets/img/bg_2.png') }}");
            background-size: cover;
            padding: 5px 12px 5px 3px;
            border: 1px solid black;
            border-radius: 20px;
            width: 95%;
            margin-bottom: 10px;
        }

        .oneDiv p {
            color: white;
            font-size: 8px;
        }

        .oneDiv span {
            color: black;
            background-color: white;
            padding: 5px;
        }

        .oneDiv h1 {
            position: relative;
            background-color: white;
            padding: 6px 7px;
            text-align: center !important;
            width: 75%;
            border-radius: 10px;
            font-size: 13px;
        }

        .oneDiv h1::after {
            position: absolute;
            content: "";
            border-top: 21px solid transparent;
            border-bottom: 20px solid transparent;
            border-left: 18px solid white;
            top: 1px;
            left: 92%;
        }

        .oneDiv2 {
            padding: 10px;
            border: 1px solid black;
            border-radius: 20px;
            width: 95%;
            margin-bottom: 10px;
        }

        .oneDiv2 p {
            color: rgb(0, 0, 0);
            font-size: 8px;
        }

        .oneDiv2 span {
            color: black;
            background-color: rgb(0, 0, 0);
            padding: 5px;
            color: white;
        }

        .oneDiv2 h1 {
            background-color: rgb(0, 0, 0);
            padding: 5px;
            color: white;
            text-align: center !important;
            font-size: 13px;
        }

        .oneDiv3 {
            padding: 8px;
            border: 1px solid black;
            border-radius: 20px;
            width: 95%;
            margin-bottom: 10px;
            background-color: white;
        }

        .oneDiv3 p {
            color: rgb(0, 0, 0);
            font-size: 8px;
        }

        .oneDiv3 span {
            color: black;
            background-color: rgb(0, 0, 0);
            padding: 5px;
            color: white;
        }

        .oneDiv3 h1 {
            color: red;
            text-align: center !important;
            font-size: 27px;
        }

        .oneDivTD {
            margin-top: -225px;
            margin-left: -10px;
        }

        /* .arrow-right {
      width: 0;
      height: 0;

    } */
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="padding: 10px;">
                <div class="textdiv oneDivTD">
                    <h1>BORVAT</h1>
                </div>

            </td>
            <td>
                <div class="secondDiv2" style="text-align: center !important;">
                    <img src="{{ public_path('storage/products/' . $product->id . '/' . $product->image) }}"
                        alt="">
                    <div class="intodiv">
                        <p><b>{{ $product->title }}</b></p>
                    </div>
                    <div class="thierdDiv">
                        <p><b>{{ $product->ean }}</b></p>
                    </div>
                </div>
            </td>
            <td>
                <div class="secondDiv">
                    {{-- fixed text --}}
                    <div class="oneDiv">
                        <h1>MADE IN <br> CHINA
                            <div class="arrow-right"></div>
                        </h1>
                        <br>
                        <p><span>SHIPPER:</span> <br><br>
                            GUANGXI ARCHI <br>
                            TECHNOLOGY CO.,LTD <br>
                            <br>
                            <span>Address:</span> <br><br>
                            6-4, Unit 2, Building 1, <br>
                            Changhong Century, <br>
                            278 pingshan Avenue,
                            Liuzhou City <br>
                            Zip code: 545001
                        </p>
                    </div>
                    {{-- fixed text --}}
                    <div class="oneDiv2">
                        <h1>IMPORTER</h1>

                        <p>
                            Borvat.com B.V. <br>
                            KVK: 87008661<br>
                            <br>
                            <span>Address:</span> <br><br>
                            Gonggrijpstraat35<br>
                            8607BD,Sneek,<br>
                            TheNetherlands
                        </p>
                    </div>
                    <div class="oneDiv3" style="height: 55px;">
                        {{-- quanttiy --}}
                        <h1>{{ $product->pivot->quantity }}</h1>
                    </div>
                    <div></div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="fristDiv2">
                    {{-- shop logo --}}
                    <div class="fristsq"
                        style="background-image: url('{{ public_path('storage/' . $shipment->account->logo) }}');background-size:cover !important;">
                    </div>
                    <div class="ozDiv">
                        @if (!$product_properties->isEmpty())
                            @foreach ($product_properties as $prop)
                                <div class="smallDivINSecond">
                                    <h2>{{ $prop->name }}</h2>
                                </div>
                            @endforeach
                        @else
                        <div class="smallDivINSecond">
                            <h2>&nbsp;</h2>
                        </div>
                        <div class="smallDivINSecond">
                            <h2>&nbsp;</h2>
                        </div>
                        <div class="smallDivINSecond">
                            <h2>&nbsp;</h2>
                        </div>
                        @endif
                    </div>
                    <div class="cePhoto">
                        <img src="{{ public_path('assets/img/cephoto.jpg') }}" alt="cephoto">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
