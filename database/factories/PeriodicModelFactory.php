<?php

$factory->define(Roomp\Periodic\Models\Availability::class, function() {
  return [
    'date' => null,
    'room_id' => null,
    'available' => 0
  ];
});

$factory->define(Roomp\Periodic\Models\Price::class, function($faker, $data) {
  return [
    'date' => null,
    'room_id' => null,
    'rate_id' => $data['rate_id'] ?? 1,
    'guest_number' => $data['guest_number'] ?? 1,
    'price' => $data['price'] ?? 0,
  ];
});


$factory->define(Roomp\Periodic\Models\ChannelPrice::class, function($faker, $data) {
  return [
    'date' => null,
    'room_id' => null,
    'rate_id' => $data['rate_id'] ?? 1,
    'guest_number' => $data['guest_number'] ?? 1,
    'price' => $data['price'] ?? 0,
  ];
});


$factory->define(Roomp\Periodic\Models\Restriction::class, function($faker, $data) {
  return [
    'date' => null,
    'room_id' => null,
    'rate_id' => $data['rate_id'] ?? 1,
    'closed' => false,
    'closed_to_arrival' => false,
    'closed_to_departure' => false,
    'minstay' => 0,
    'maxstay' => 0,
    'minstay_on_arrival' => 0
  ];
});