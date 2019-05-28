<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NCLOUD SENS Service ID
    |--------------------------------------------------------------------------
    |
    | Service ID used to authenticate the SENS api request.
    |
    */
    'service_id' => env('SENS_SERVICE_ID', ''),

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