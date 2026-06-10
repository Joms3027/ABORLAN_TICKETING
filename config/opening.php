<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Opening Ceremony
    |--------------------------------------------------------------------------
    |
    | When enabled, visitors must complete the opening ceremony (countdown +
    | biometric scan) before accessing the site. Set OPENING_CEREMONY_ENABLED
    | to false after the launch event.
    |
    */

    'enabled' => env('OPENING_CEREMONY_ENABLED', false),

    'opens_at' => env('SITE_OPENING_AT', '2026-06-10 09:00:00'),

    'timezone' => env('OPENING_TIMEZONE', env('APP_TIMEZONE', 'Asia/Manila')),

    'cookie_name' => 'aborlan_opening_passed',

    'cookie_minutes' => (int) env('OPENING_COOKIE_MINUTES', 525600), // ~1 year

];
