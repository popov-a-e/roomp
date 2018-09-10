<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\Facades\Restriction;
use Roomp\Hotels\Hotel;
use Roomp\OTA\Wired\Agent as Wubook;
use Roomp\OTA\Ostrovok\Agent as Ostrovok;

class SyncRestrictions extends Command {
  protected $signature = 'sync:restrictions {id}';

  protected $description = 'Sync Hotel Restrictions';

  private $wubook;
  private $ostrovok;
  private $hotels;

  public function __construct(Ostrovok $ostrovok, Wubook $wubook, Hotel $hotels) {
    parent::__construct();
    $this->wubook = $wubook;
    $this->ostrovok = $ostrovok;
    $this->hotels = $hotels;
  }

  public function handle() {
    $restrictions = Restriction::from(now())
      ->till(now()->addDay(10))
      ->atRooms(
        $this->hotels->find($this->argument('id'))->rooms->pluck('id')->toArray()
      )
      //->setRate(1)
      ->collect()
      ->groupBy(['room_id', 'rate_id'])
      ->proxyMap->map
      ->toDateInterval()
      ->collapseTimes(2);

    $ostrovokRestrictions = $restrictions->map(function($r) {
      return (object)$r;
    })->toArray();

    $wubookRestrictions = $restrictions->map(function($r) {
      foreach ([
                 ['closed' => 'closed'],
                 ['closed_to_arrival' => 'cta'],
                 ['closed_to_departure' => 'ctd'],
                 ['minstay' => 'minstay'],
                 ['maxstay' => 'maxstay'],
                 ['minstay_on_arrival' => 'minstayarr'],
               ] as $key => $value) {
        foreach($value as $roomp => $wubook) {
          $r['params'][$wubook] = $r[$roomp];

          unset ($r[$roomp]);
        }
      }

      return (object)$r;
    })->toArray();

    $this->wubook->setRestrictions($wubookRestrictions);

    //$this->ostrovok->setRestrictions($hotel->id, $ostrovokRestrictions);
  }
}
