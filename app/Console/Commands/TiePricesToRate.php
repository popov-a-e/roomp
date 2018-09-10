<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Roomp\Facades\ChannelPrice;
use Roomp\Facades\Price;
use Roomp\Hotels\Hotel;

class TiePricesToRate extends Command {
  protected $signature = 'prices:tie {id}';

  protected $description = 'Tie prices to rate';
  private $hotels;

  public function __construct(Hotel $hotel) {
    parent::__construct();
    $this->hotels = $hotel;
  }

  public function handle() {
    $hotel = $this->hotels->find($this->argument('id'));

    ChannelPrice::from(now())
      ->till(now()->addYear()->endOfYear())
      ->atRooms($hotel->rooms()->pluck('id')->toArray())
      ->collect()
      ->map(function($price) use ($hotel) {
        $price->price *= $hotel->priceKoef;
        if ($price->price <= 0) $price->price = 0;

        return $price;
      })
      ->groupBy(['room_id', 'rate_id', 'guest_number'])
      ->proxyMap->proxyMap->map->map
      ->toDateInterval()
      ->collapseTimes(3)
      ->each(function($price) {
        Price::from(carbon($price['from']))
          ->till(carbon($price['till']))
          ->atRoom($price['room_id'])
          ->setRate($price['rate_id'])
          ->setGuestNumber($price['guest_number'])
          ->set($price['price']);
      });

    Artisan::call('sync:prices', ['id' => $hotel->id]);
  }
}
