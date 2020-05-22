<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NCLOUD SENS Service ID for SMS or LMS
    |--------------------------------------------------------------------------
    |
    | Service ID used to authenticate the SENS api request.
    |
    */
    'service_id' => env('SENS_SERVICE_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | NCLOUD SENS Service ID for AlimTalk
    |--------------------------------------------------------------------------
    |
    | Service ID used to authenticate the SENS AlimTalk api request.
    | SMS service ID is not same with this AlimTalk service ID.
    */
    'alimtalk_service_id' => env('SENS_ALIMTALK_SERVICE_ID', ''),
    'plus_friend_id' => env('SENS_PlUS_FRIEND_ID', '@id'),

    /*
    |--------------------------------------------------------------------------
    | NCLOUD SENS Access Key
    |--------------------------------------------------------------------------
    |
    | Access key used to authenticate the SENS api request.
    |
    */
    'access_key' => env('SENS_ACCESS_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | NCLOUD SENS Secret Key
    |--------------------------------------------------------------------------
    |
    | Secret key used to authenticate the SENS api request.
    |
    */
    'secret_key' => env('SENS_SECRET_KEY', ''),

];
