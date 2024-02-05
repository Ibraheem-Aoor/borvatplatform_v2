<?php

return [

    'keeny_delivery_api' => [

        'base_url' => 'https://portal.keendelivery.com/api/v2/',

        'token' => env('KEEN_DELLIVERY_API_TOKEN'),
        'auth_type' => 'berar_token',

        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
    ],
    'pro_parcel' => [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'login_id' => env('PRO_PARCEL_LOGIN_ID'),
        'token' => env('PRO_PARCEL_TOKEN'),
        'base_url' => 'https://login.parcelpro.nl/api',
    ],

    'zianpesly' => [
        'username' => env('ZINAPESLY_USERNAME'),
        'password' => env('ZINAPESLY_PASSWORD'),
        'auth_type' => 'none', #Send auth Data in request body.
        'base_url' => 'https://api.zineps.com/',
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
    ],

    'qls' => [
        'username' => env('QLS_USERNAME'),
        'password' => env('QLS_PASSWORD'),
        'auth_type' => 'basic_auth', #Send auth Data in request body.
        'base_url' => 'https://api.pakketdienstqls.nl/',
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'borvat_company_id' => '5f636752-19c2-4399-8e05-5a562f366086',
    ],
];
