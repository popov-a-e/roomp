<?php

use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder {
  public function run() {
    $arr = [
      ['name' => 'reserved', 'online' => false, 'active' => true, 'confirmed' => false],
      ['name' => 'reserved_online', 'online' => true, 'active' => true, 'confirmed' => false],
      ['name' => 'booked', 'online' => false, 'active' => true, 'confirmed' => true],
      ['name' => 'paid', 'online' => true, 'active' => true, 'confirmed' => true],
      ['name' => 'cancelled', 'online' => false, 'active' => false, 'confirmed' => true],
      ['name' => 'cancelled_online', 'online' => true, 'active' => false, 'confirmed' => true],
      ['name' => 'not_confirmed', 'online' => false, 'active' => false, 'confirmed' => false],
      ['name' => 'not_confirmed_online', 'online' => true, 'active' => false, 'confirmed' => false],
    ];

    $rmp = new \Roomp\Channel;

    $rmp->name = 'Roomp';

    $rmp->save();

    \Roomp\Status::insert($arr);
  }
}
