<?php

use App\Enums\SalesUnit;

return [
//    'kwyc-check' => [
//        'campaign_code' => env('CAMPAIGN_CODE', '9d45e2fc-4d4a-4905-ab53-5ff8599f77ff"')
//    ],
    'defaults' => [
        'booking_server' => env('DEFAULT_BOOKING_SERVER'),
        'authentication_server' => env('DEFAULT_AUTHENTICATION_SERVER', 'kwyc-check.net'),
        'sales_unit' => SalesUnit::tryFrom(env('DEFAULT_SALES_UNIT', SalesUnit::default()->value)),
        'contact_register' => env('CONTACT_REGISTER', 'https://contacts.homeful.ph/register'),
        'contract_callback' => env('CONTRACT_CALLBACK', 'https://contracts.homeful.ph/consult/create')
    ],
    'campaign_code' => [
        SalesUnit::EXE->name => env('CAMPAIGN_CODE_EXE', '9d6c449e-1fd2-404f-a436-efd0b2d1ac2d'),
        SalesUnit::EYE->name => env('CAMPAIGN_CODE_EYE','9d6c4660-9617-42e6-8641-afde11ba7e8e'),
        SalesUnit::EVE->name => env('CAMPAIGN_CODE_EVE', '9d6c4746-9b7d-4103-a017-0f7da2691807'),
    ],
    'booking_server' => [
        SalesUnit::EXE->name => env('BOOKING_SERVER_EXE', 'book.extraordinaryenclaves.ph'),
        SalesUnit::EYE->name => env('BOOKING_SERVER_EYE','book.everyhomeenclaves.ph'),
        SalesUnit::EVE->name => env('BOOKING_SERVER_EVE', 'book.elanvitalenclaves.ph'),
    ],
];
