<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\CRS\RatePlan;
use Roomp\Hotels\Hotel;
use Roomp\OTA\Wired\Agent as Wubook;
use Roomp\OTA\Ostrovok\Agent as Ostrovok;

class CloseAvailability extends Command {

  protected $signature = 'availability:close {id}';

  protected $description = 'Closes Availability through channels';

  private $wubook;
  private $ostrovok;
  private $hotels;

  public function __construct(Ostrovok $ostrovok, Wubook $wubook, Hotel $hotels, RatePlan $ratePlan) {
    parent::__construct();
    $this->wubook = $wubook;
    $this->ostrovok = $ostrovok;
    $this->hotels = $hotels;
  }

  public function handle() {
    $hotel = $this->hotels->find($this->argument('id'));

    $closedRestriction = [];

    $from = now()->toDateString();
    $till = now()->addYear()->endOfYear();

    foreach ($hotel->rooms as $room) {
      foreach($hotel->rates as $rate) {
        $closedRestriction[] = (object)[
          "from" => $from,
          "till" => $till,
          "rate_id" => $rate->id,
          "room_id" => $room->id,
          "params" => (object)[
            "closed" => true
          ]
        ];
      }
    }

    $this->wubook->setRestrictions($closedRestriction, true);
  }
}
