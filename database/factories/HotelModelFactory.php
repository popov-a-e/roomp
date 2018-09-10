<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:38
 */

$factory->define(Roomp\Hotel::class, function (Faker\Generator $faker) {
  return [
    'ru' => 'Roomp '.$faker->unique()->streetAddress,
    'address_ru' => $faker->address,
    'hotelier_id' => null, //filled from seeder
    'city_id' => null, //filled from seeder
    'regular_name' => $faker->unique()->streetAddress,
    'description_ru' => $faker->text,
    'lat' => $faker->latitude,
    'lng' => $faker->longitude,
    'reception_phone' => $faker->phoneNumber,
    'reception_email' => $faker->safeEmail,
    'head_phone' => $faker->phoneNumber,
    'head_email' => $faker->safeEmail,
    'reach_ru' => $faker->text,
    'map' => $faker->imageUrl(),
    'landmark_ru' => $faker->text,
    'additional_ru' => $faker->text,
    'photos' => $faker->imageUrl(),
    'checkin' => $faker->time(),
    'checkout' => $faker->time(),
    'disabled' => false,
    'pretty_url' => implode('_',$faker->unique()->words(4))
  ];
});

$factory->define(Roomp\HotelAmenity::class, function(Faker\Generator $faker) {
  return [
    'ru' => $faker->unique()->word
  ];
});

$factory->define(Roomp\Policy::class, function(Faker\Generator $faker) {
  return [
    'bed_number' => $faker->numberBetween(0, 3),
    'price_infants' => $faker->numberBetween(100, 1000),
    'price_children' => $faker->numberBetween(100, 1000),
    'price_adults' => $faker->numberBetween(100, 1000),
    'age_children' => $faker->numberBetween(6,14),
    'hotel_id' => null
  ];
});