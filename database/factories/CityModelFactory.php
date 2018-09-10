<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:41
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roomp\City::class, function (Faker\Generator $faker) {
  return [
    'ru' => $faker->city,
    'lat' => $faker->latitude,
    'lng' => $faker->longitude,
    'utc_offset' => $faker->numberBetween(-23,23)/2,
    'active' => true
  ];
});
