<?php

return [
  'default' => env('FILE_DRIVER', 'local'),

  'cloud' => env('FILE_CLOUD', 's3'),

  'disks' => [

    'local' => [
      'driver' => 'local',
      'root' => storage_path('app'),
    ],

    'public' => [
      'driver' => 'local',
      'root' => storage_path('app/public'),
      'url' => env('APP_URL') . '/storage',
      'visibility' => 'public',
    ],

    's3' => [
      'driver' => 's3',
      'key' => env('AWS_ACCESS_KEY_ID'),
      'secret' => env('AWS_SECRET_ACCESS_KEY'),
      'region' => env('AWS_DEFAULT_REGION'),
      'bucket' => env('AWS_BUCKET'),
      'url' => env('AWS_URL'),
    ],

    'dev' => [
      'driver' => 's3',
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      'region' => env('AWS_REGION'),
      'bucket' => 'roomp-dev',
    ],

    'prod' => [
      'driver' => 's3',
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      'region' => env('AWS_REGION'),
      'bucket' => 'roomp-prod',
    ],
  ],

];
