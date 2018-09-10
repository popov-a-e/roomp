<?php

namespace Roomp\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Roomp\OTA\Wired\Account;
use Roomp\OTA\Wired\Connection;
use Roomp\OTA\Wired\Driver;
use Roomp\Events\WubookConnectionBroken;

class CheckWubookConnections extends Command {
  protected $signature = 'check_wubook_connections';

  protected $description = 'Checking if wubook connections are active';
  private $driver;

  public function __construct(Driver $driver) {
    $this->driver = $driver;
    parent::__construct();
  }

  public function handle() {
    $accounts = Account::all();

    $accounts->each(function($account) {
      $otas = collect($this->driver->getOTAs($account->lcode));

      $otas->each(function ($ota) {
        $connection = Connection::where('chid', $ota['id'])->first();
        $active = $ota['running'] === 1;

        if (!$connection) return;

        if ($connection->last_active && !$active) {
          event(new WubookConnectionBroken($connection->hotel_id));
        }

        $connection->last_active = $active ? Carbon::now() : null;
        $connection->save();
      });
    });
  }
}
