<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\Facades\Availability;
use Roomp\Hotels\Hotel;
use Roomp\OTA\Wired\Agent as Wubook;
use Roomp\OTA\Ostrovok\Agent as Ostrovok;


class SyncAvailability extends Command {
  protected $signature = 'sync:availability {id}';

  protected $description = 'Sync Hotel Availability';

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

    $avail = Availability::from(now())
      ->till(now()->addYear()->endOfYear())
      ->atRooms(
        $this->hotels->find($id)->rooms->pluck('id')->toArray()
      )
      ->collect()
      ->groupBy('room_id')
      ->proxyMap
      ->map
      ->only('date', 'available')
      ->toArray();

    $this->wubook->setAvailability($id, $avail);

    if ($this->ostrovok->isConnected($id)) {
      $this->ostrovok->setAvailability($id, $avail);
    }
  }
}
