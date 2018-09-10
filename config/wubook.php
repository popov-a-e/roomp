<?php

return [
  'login' => env('WOODOO_LOGIN'),
  'password' =>  env('WOODOO_PASSWORD'),
  'channel_id' => 136,

  'cm' => [
    'username' => env('WIRED_LOGIN'),
    'password' => env('WIRED_PASSWORD'),
    'provider_key' => env('WIRED_PKEY'),
    'push_url' => 'https://api.roomp.co/v1/wubook/push',

    'lcode' => 1501655009
  ],
];