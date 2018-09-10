<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:38
 */

$factory->define(Roomp\Hotelier::class, function (Faker\Generator $faker) {
  static $password;

  return [
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'password' => $password ?: $password = bcrypt('secret'),
    'phone' => $faker->unique()->phoneNumber,
    'organization' => $faker->company
  ];
});