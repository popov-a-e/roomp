<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationsSeeder extends Seeder {
  public function run() {
    $hotels = DB::table('hotels')->select('id')->pluck('id');
    $rooms = \Roomp\Room::all();

    $users = $this->seedUsers();
    $this->seedReservations($users, $hotels, $rooms, 100);
  }


  private function seedUsers() {
    return factory(Roomp\User::class, 20)->create();
  }

  private function seedReservations($users, $hotels, $rooms, $n = 100) {
    for ($i = 0; $i < $n; ++$i) {
      $hotelId = $hotels->random();

      $roomsThisHotel = $rooms->filter(function ($room) use ($hotelId) {
        return $room->hotel_id === $hotelId;
      });

      $daysFromNow = rand(1, 100);
      $reservationDuration = rand(1, 30);

      $from = Carbon::now()->addDays($daysFromNow);
      $till = $from->copy()->addDays($reservationDuration);

      $roomNumber = rand(1, 5);

      $prices = array_map(function () {
        return rand(1000, 10000);
      }, range(1, $roomNumber));

      $priceTotal = array_reduce($prices, function ($sum, $el) {
        return $el + $sum;
      }, 0);

      $reservationParams = [
        'user_id' => $users->random()->id,
        'hotel_id' => $hotelId,
        'from' => $from,
        'till' => $till
      ];

      $cancelled = (rand(1, 100) >= 50);
      if ($cancelled) {
        $reservationParams['created_at'] = Carbon::now();
      }

      $reservationID = factory(Roomp\Reservation::class)->create($reservationParams)->id;

      $this->seedOccupancyForReservation($reservationID, $roomsThisHotel, $from, $till, $prices, $roomNumber);

      factory(Roomp\StatusLog::class)->create([
        'reservation_id' => $reservationID,
        'user_id' => $reservationParams['user_id']
      ]);
    }
  }

  private function seedOccupancyForReservation($reservationID, $rooms, $from, $till, $prices, $n) {
    for ($i = 0; $i < $n; ++$i) {
      $room = $rooms->random();

      $nights = $till->diffInDays($from);

      $occ = factory(\Roomp\CRS\Occupancy::class)->create([
        'room_id' => $room->id,
        'reservation_id' => $reservationID,
        'allotment_id' => $room->allotments->random()->id,
        //'guest_number' => rand(1, $room->max_guest_number),
        'prices' => array_fill(0, $nights - 1, $prices[$i])
      ]);

      $occ->actualizePrice();
      $occ->calculateRates();

      //$this->reduceAvailabilityForOccupancy($room, $from, $till);
    }
  }

  private function reduceAvailabilityForOccupancy($room, $from, $till) {
    DB::table('availability')
      ->where('hotel_id', $room->hotel_id)
      ->where('room_id', $room->id)
      ->where('date', '>=', $from)
      ->where('date', '<=', $till)
      ->update([
        'available' => DB::raw('available - 1')
      ]);
  }

}