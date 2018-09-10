<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:41
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roomp\User::class, function (Faker\Generator $faker) {
  static $password;

  return [
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'phone' => $faker->phoneNumber,
    'password' => $password ?: $password = bcrypt('secret'),
    'remember_token' => str_random(10)
  ];
});
