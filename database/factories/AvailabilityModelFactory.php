<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.02.2017
 * Time: 14:41
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roomp\Availability::class, function () {
  return [
    'date' => null,
    'room_id' => null,
    'hotel_id' => null,
    'available' => null
  ];
});
