<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\Facades\Price;
use Roomp\Hotels\Hotel;
use Roomp\OTA\Wired\Agent as Wubook;
use Roomp\OTA\Ostrovok\Agent as Ostrovok;

class SyncPrices extends Command {
  protected $signature = 'sync:prices {id}';

  protected $description = 'Sync Hotel Prices';

  private $wubook;
  private $ostrovok;
  private $hotels;

  public function __construct(Ostrovok $ostrovok, Wubook $wubook, Hotel $hotel) {
    parent::__construct();

    $this->wubook = $wubook;
    $this->ostrovok = $ostrovok;
    $this->hotels = $hotel;
  }

  public function handle() {
    $id = $this->argument('id');

    $prices = Price::from(now())
      ->till(now()->endOfYear()->addYear())
      ->atRooms(
        $this->hotels
          ->find($id)
          ->rooms()
          ->pluck('id')
          ->toArray()
      )
      ->collect()
      ->groupBy(['room_id', 'rate_id', 'guest_number'])
      ->proxyMap->proxyMap->map->map
      ->toDateInterval()
      ->collapseTimes(3)
      ->reject(function($p) {return $p['price'] <= 0;})
      ->toArray();

    if ($this->ostrovok->isConnected($id)) {
      $this->ostrovok->setPrices($id, $prices);
    }

    $this->wubook->setPrices($id, $prices);

    //$this->wubook->setRestrictions($restrictions->toArray());
  }
}
