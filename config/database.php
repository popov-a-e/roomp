<?php

return [
  'default' => env('DB_CONNECTION', 'mysql'),

  'connections' => [
    'pgsql' => [
      'driver' => 'pgsql',
      'host' => env('DB_HOST', '127.0.0.1'),
      'port' => env('DB_PORT', '5432'),
      'database' => env('DB_DATABASE', 'forge'),
      'username' => env('DB_USERNAME', 'forge'),
      'password' => env('DB_PASSWORD', ''),
      'charset' => 'utf8',
      'prefix' => '',
      'schema' => 'public',
      'sslmode' => 'prefer',
    ],
  ],

  'migrations' => 'migrations',
  'redis' => [

    'client' => 'predis',

    'default' => [
      'host' => env('REDIS_HOST', '127.0.0.1'),
      'password' => env('REDIS_PASSWORD', null),
      'port' => env('REDIS_PORT', 6379),
      'database' => 0,
    ],

  ],

];
