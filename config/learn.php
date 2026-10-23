<?php

return [

    /*
    | 運営者（admin）の初期アカウント。AdminSeeder が使う。
    | 未設定なら運営者は作らない。
    */
    'admin' => [
        'name' => env('ADMIN_NAME', '運営者'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
