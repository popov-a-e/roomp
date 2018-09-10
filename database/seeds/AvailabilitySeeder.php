<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder {
  public function run() {
    $rooms = DB::table('rooms')->select('*')->get();

    $this->seedWorkload($rooms);
  }

  private function seedWorkload($rooms, $limit = '2019-12-31') {
    $rooms->each(function ($room, $j) use ($limit) {
      for ($i = Carbon::now(); $i <= $limit; $i->addDay()) {
        factory(Roomp\Availability::class)->create([
          'date' => $i,
          'room_id' => $room->id,
          'hotel_id' => $room->hotel_id,
          'available' => $room->number
        ]);
      }
    });
  }
}