<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:41
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roomp\Price::class, function (Faker\Generator $faker) {
  return [
    'date' => null,
    'hotel_id' => null,
    'room_id' => null,
    'price' => $faker->numberBetween(1000,10000)
  ];
});
