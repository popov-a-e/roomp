<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PricesSeeder extends Seeder {
  public function run() {
    $rooms = DB::table('rooms')->select('*')->get();

    $this->seedPrices($rooms);
  }

  private function seedPrices($rooms, $limit = '2019-12-31') {
    $limit = Carbon::parse($limit);
    $rooms->each(function ($room) use ($limit) {
      for ($i = Carbon::now(); $i->diffInDays($limit, false) > 0; $i->addDay()) {
        $price_value = rand(1000, 6000);
        for ($j = 1; $j <= $room->max_guest_number; ++$j) {
          $price_value += rand(1, 1000);
          $price = factory(Roomp\Price::class)->create([
            'date' => $i,
            'room_id' => $room->id,
            'hotel_id' => $room->hotel_id,
            'price' => $price_value,
            'guest_number' => $j
          ]);
        }
      }
    });
  }
}