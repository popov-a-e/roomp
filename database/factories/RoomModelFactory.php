<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:38
 */

$factory->define(Roomp\Room::class, function (Faker\Generator $faker) {
  return [
    'name' => $faker->numberBetween(1,100),
    'hotel_id' => null,
    'room_class_id' => rand(1,3),
    'photos' => '',
    'number' => $faker->numberBetween(1, 4),
  ];
});

$factory->define(Roomp\Allotment::class, function(Faker\Generator $faker) {
  return [
    'ru' => $faker->unique()->word
  ];
});

$factory->define(Roomp\RoomClass::class, function(Faker\Generator $faker) {
  return [
    'ru' => $faker->unique()->word
  ];
});

$factory->define(Roomp\RoomAmenity::class, function(Faker\Generator $faker) {
  return [
    'ru' => $faker->unique()->word
  ];
});