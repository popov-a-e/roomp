<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:41
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roomp\Reservation::class, function (Faker\Generator $faker) {
  $code = $faker->unique()->numerify(strtoupper(str_random(4)).'####',true);

  return [
    'code' => $code,
    'hotel_id' => null,
    'guest_name' => $faker->name,
    'token' => str_random(),
    'cancel_token' => str_random(),
    'comment' => $faker->text(),
    'from' => null,
    'till' => null,
    'user_id' => null,
    'channel_id' => 1
  ];
});

$factory->define(\Roomp\CRS\Occupancy::class, function(Faker\Generator $faker) {
  return [
    'room_id' => null,
    'reservation_id' => null,
    'allotment_id' => null,
    'prices' => []
  ];
});

$factory->define(Roomp\StatusLog::class, function(Faker\Generator $faker) {
  return [
    'status_id' => $faker->numberBetween(1,8),
    'reservation_id' => null,
    'user_id' => null,
    'guard' => 'users',
    'timestamp' => $faker->dateTime
  ];
});