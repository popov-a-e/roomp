<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelsSeeder extends Seeder {
  public function run () {
    $cities = $this->seedCities(3);
    $hoteliers = $this->seedHoteliers(1);

    $hotels = $this->seedHotels($cities, $hoteliers, 10);

    $hotelAmenities = $this->seedHotelAmenities();

    $this->seedHotelsAmenities($hotels, $hotelAmenities);

    $this->seedPolicies($hotels);
  }

  private function seedCities($n = 5) {
    return factory(Roomp\City::class, $n)->create();
  }

  private function seedHoteliers($n = 10) {
    return factory(Roomp\Hotelier::class, $n)->create();
  }

  private function seedHotels($cities, $hoteliers, $n = 20) {
    $hotels = collect([]);
    for ($i = 0; $i < $n; ++$i) {
      $hotel_id = factory(Roomp\Hotel::class)->create([
        'city_id' => $cities->random()->id,
        'hotelier_id' => $hoteliers->random()->id
      ])->id;

      $hotels->push($hotel_id);
    }

    return $hotels;
  }

  private function seedHotelAmenities($n = 10) {
    $amenities = collect();
    for ($i = 0; $i < $n; ++$i) {
      $amenity_id = factory(Roomp\HotelAmenity::class)->create()->id;
      $amenities->push($amenity_id);
    }

    return $amenities;
  }

  private function seedHotelsAmenities($hotels, $amenities, $probability = 0.4) {
    $hotels->each(function ($hotel_id) use ($amenities, $probability) {
      $amenities->each(function ($amenity_id) use ($hotel_id, $probability) {
        if (rand(1, 100) >= ($probability * 100)) return;

        DB::table('hotels_amenities')->insert([
          'hotel_id' => $hotel_id,
          'amenity_id' => $amenity_id
        ]);
      });
    });
  }

  private function seedPolicies($hotels) {
    $hotels->each(function($hotelID) {
      factory(Roomp\Policy::class)->create(['hotel_id' => $hotelID]);
    });
  }
}