<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cart Mode
    |--------------------------------------------------------------------------
    |
    | Cart mode
    | 
    | supports: "single", "steps"
    |
    */

    'cart_mode' => 'single',

    /*
    |--------------------------------------------------------------------------
    | Async
    |--------------------------------------------------------------------------
    |
    | Async cart at the side
    | 
    | supports: boolean
    |
    */

    'async' => false,

    /*
    |--------------------------------------------------------------------------
    | Fast forward delivery & payment
    |--------------------------------------------------------------------------
    |
    | Fast forward delivery & payment, only single delivery and single payment
    | is available.
    | 
    | supports: boolean
    |
    */

    'ff_delivery_payment' => false,

    /*
    |--------------------------------------------------------------------------
    | Guess country
    |--------------------------------------------------------------------------
    |
    | Guess country based on user's location. 
    | 
    | supports: boolean
    |
    */

    'guess_country' => true,
    
    'netimpact_key' => 'G5PDtywaRFFcpbfF',

    /*
    |--------------------------------------------------------------------------
    | Default Order status
    |--------------------------------------------------------------------------
    |
    | Default Order status id.
    | 
    | supports: integer (existing status)
    |
    */

    'default_order_status' => 1,

    /*
    |--------------------------------------------------------------------------
    | Default Payment type
    |--------------------------------------------------------------------------
    |
    | Default payment type, if none is selected.
    | 
    | supports: integer (existing status)
    |
    */

    'default_payment_type' => 1,

    /*
    |--------------------------------------------------------------------------
    | Slips
    |--------------------------------------------------------------------------
    |
    | Show order slips in profile
    | 
    | supports: boolean
    |
    */

    'show_slips' => false, 

    'only_logged_in' => false,

];
