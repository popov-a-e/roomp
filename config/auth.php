<?php

return [
  'defaults' => [
    'guard' => 'users',
    'passwords' => 'users',
  ],

  'guards' => [
    'users' => [
      'driver' => 'session',
      'provider' => 'users',
    ],

    'api' => [
      'driver' => 'session',
      'provider' => 'api',
    ],

    'hoteliers' => [
      'driver' => 'session',
      'provider' => 'hoteliers'
    ],

    'admins' => [
      'driver' => 'session',
      'provider' => 'admins'
    ],

    'channels' => [
      'driver' => 'session',
      'provider' => 'channels'
    ],

    'bd' => [
      'driver' => 'session',
      'provider' => 'bd'
    ]
  ],


  'providers' => [
    'users' => [
      'driver' => 'eloquent',
      'model' => Roomp\User::class,
    ],

    'api' => [
      'driver' => 'eloquent',
      'model' => Roomp\APIUser::class
    ],

    'hoteliers' => [
      'driver' => 'eloquent',
      'model' => Roomp\Hotelier::class
    ],

    'admins' => [
      'driver' => 'eloquent',
      'model' => Roomp\Admin::class
    ],
    /*
          'channels' => [
            'driver' => 'eloquent',
            'model' => Roomp\Channel::class
          ],*/

    'bd' => [
      'driver' => 'eloquent',
      'model' => Roomp\BusinessDeveloper::class
    ]
    // 'users' => [
    //     'driver' => 'database',
    //     'table' => 'users',
    // ],
  ],

  'passwords' => [
    'users' => [
      'provider' => 'users',
      'table' => 'password_resets',
      'expire' => 60,
    ],
  ],

];
