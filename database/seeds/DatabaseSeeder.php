<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  public function run() {
    $this->call(HotelsSeeder::class);
    $this->call(RoomsSeeder::class);
    $this->call(PricesSeeder::class);
    $this->call(AvailabilitySeeder::class);
    $this->call(StatusesSeeder::class);
    $this->call(ReservationsSeeder::class);
  }
}

