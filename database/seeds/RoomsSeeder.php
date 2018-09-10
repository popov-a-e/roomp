<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder {
  public function run() {
    $hotels = DB::table('hotels')->select('id')->pluck('id');

    $roomAmenities = $this->seedRoomAmenities();

    $allotments = $this->seedAllotments(2);

    $roomClasses = $this->seedRoomClasses(3);

    $rooms = $this->seedRooms($hotels, $roomClasses, $allotments);

    $this->seedRoomsAmenities($rooms, $roomAmenities);
  }

  private function seedRoomAmenities($n = 10) {
    $amenities = collect();
    for ($i = 0; $i < $n; ++$i) {
      $amenity_id = factory(Roomp\RoomAmenity::class)->create()->id;
      $amenities->push($amenity_id);
    }

    return $amenities;
  }

  private function seedRoomsAmenities($rooms, $amenities, $probability = 0.4) {
    $rooms->each(function ($room) use ($amenities, $probability) {
      $amenities->each(function ($amenity_id) use ($room, $probability) {
        if (rand(1, 100) >= ($probability * 100)) return;

        DB::table('rooms_amenities')->insert([
          'room_id' => $room->id,
          'amenity_id' => $amenity_id
        ]);
      });
    });
  }

  private function seedAllotments($n = 3) {
    return factory(Roomp\Allotment::class, $n)->create();
  }

  private function seedRoomClasses($n = 4) {
    return factory(Roomp\RoomClass::class, $n)->create();
  }

  private function seedRooms($hotels, $roomClasses, $allotments, $n = 4) {
    $rooms = collect([]);

    $probability = $n / $roomClasses->count();

    $hotels->each(function ($hotelID) use ($roomClasses, $allotments, &$rooms, $probability) {
      $count = 0;
      $roomClasses->each(function ($roomClass) use (&$rooms, $allotments, $probability, $hotelID, &$count) {
        if ($probability * 100 < rand(1, 100)) return;


        $room = factory(Roomp\Room::class)->create([
          'room_class_id' => $roomClass->id,
          'hotel_id' => $hotelID,
          'max_guest_number' => rand(1, 4),
          'number' => rand(1, 10)
        ]);

        do {

          do {
            $room_allotments = $allotments->filter(function() {
              return rand(1,10) < 2;
            })->map(function($allotment) use ($room) {
              return [
                'room_id' => $room->id,
                'allotment_id' => $allotment->id
              ];
            });
          } while ($room_allotments->count() == 0);


          DB::table('rooms_allotments')->insert($room_allotments->toArray());
        } while (rand(1,10) < 2);

        ++$count;

        $rooms->push($room);
      });
    });

    return $rooms;
  }
}