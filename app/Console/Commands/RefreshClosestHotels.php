<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Roomp\Hotels\Hotel;

class RefreshClosestHotels extends Command {
  protected $signature = 'hotels:closest';

  protected $description = 'Refresh the table of closest hotels';

  private $hotels;

  public function __construct(Hotel $hotel) {
    parent::__construct();

    $this->hotels = $hotel;
  }

  public function handle() {
    DB::table('hotels_closest')->truncate();
    $hotels = $this->hotels->where('disabled', false)->get();

    $distance = function ($lat1, $lng1, $lat2, $lng2) {
      $lat1 = deg2rad($lat1);
      $lng1 = deg2rad($lng1);
      $lat2 = deg2rad($lat2);
      $lng2 = deg2rad($lng2);

      $r = 6371000;

      $x1 = $r * sin($lng1) * cos($lat1);
      $y1 = $r * sin($lat1) * cos($lng1);
      $z1 = $r * cos($lat1);

      $x2 = $r * sin($lng2) * cos($lat2);
      $y2 = $r * sin($lat2) * cos($lng2);
      $z2 = $r * cos($lat2);

      $d = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2) + pow($z1 - $z2, 2));

      return round(rad2deg(acos(($r * $r * 2 - $d * $d) / (2 * $r * $r))) * $r * pi() / 360);
    };

    if ($hotels->count() < 2) return;

    $hotels->each(function ($hotel) use ($hotels, $distance) {
      $distances = $hotels->map(function ($h) use ($hotel, $distance) {
        if ($h->id === $hotel->id) return 0;

        return [
          'distance' => $distance($h->lat, $h->lng, $hotel->lat, $hotel->lng),
          'id' => $h->id
        ];
      })->filter(function ($h) {
        return $h !== 0;
      })->sortBy('distance')->values();

      $d1 = $distances[1] ?? $distances[0];
      $d2 = $distances[2] ?? $distances[1] ?? $distances[0];

      DB::table('hotels_closest')->insert([[
        'hotel_id' => $hotel->id,
        'closest_id' => $d1['id']
      ], [
        'hotel_id' => $hotel->id,
        'closest_id' => $d2['id']
      ]]);
    });
  }
}
