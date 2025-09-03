<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General PWA Settings
    |--------------------------------------------------------------------------
    */
    'name' => 'Sistema LaCajeta',
    'short_name' => 'LaCajeta',
    'start_url' => '/',
    'background_color' => '#ffffff',
    'theme_color' => '#6b21a8',
    'display' => 'standalone',
    'orientation'=> 'any',
    'status_bar'=> 'black',

    /*
    |--------------------------------------------------------------------------
    | Icons (deben estar en public/images)
    |--------------------------------------------------------------------------
    */
    'icons' => [
        '72x72'   => '/images/icon.png',
        '96x96'   => '/images/icon.png',
        '128x128' => '/images/icon.png',
        '144x144' => '/images/icon.png',
        '152x152' => '/images/icon.png',
        '192x192' => '/images/icon.png',
        '384x384' => '/images/icon.png',
        '512x512' => '/images/icon.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Splash Screens (opcional)
    |--------------------------------------------------------------------------
    */
    'splash' => [
        '640x1136' => '/images/icon.png',
        '750x1334' => '/images/icon.png',
        '828x1792' => '/images/icon.png',
        '1125x2436'=> '/images/icon.png',
        '1242x2208'=> '/images/icon.png',
        '1242x2688'=> '/images/icon.png',
        '1536x2048'=> '/images/icon.png',
        '1668x2224'=> '/images/icon.png',
        '1668x2388'=> '/images/icon.png',
        '2048x2732'=> '/images/icon.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom
    |--------------------------------------------------------------------------
    */
    'custom' => []
];
