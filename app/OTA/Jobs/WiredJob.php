<?php

namespace Roomp\OTA\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Roomp\Drivers\OTA\Wubook\Driver;

class WiredJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $params;

  public function __construct($params) {
    $this->queue = 'roomp-prod-wired';
    $this->params = $params;
  }

  public function handle(Driver $driver) {
    $params = (object)$this->params;

    switch ($params->type) {
      case 'restrictions':
        $pid = $params->pid;
        $lcode = $params->lcode;
        $from = $params->from;
        $restrictions = json_decode($params->restrictions);

        $driver->setRestrictions($pid, $lcode, $from, json_decode(json_encode($restrictions)));
        break;
      case 'availability':
        $lcode = $params->lcode;
        $from = $params->from;
        $rooms = $params->rooms;

        $driver->setAvailability($lcode, $from, $rooms);
        break;
      case 'prices':
        $pid = $params->pid;
        $lcode = $params->lcode;
        $from = $params->from;
        $prices = json_decode($params->prices);

        $driver->setPrices($pid, $lcode, $from, $prices);
        break;
    }
  }

  public function failed(\Exception $exception) {
    file_put_contents('/var/log/roomp/wired_errors.log', $exception->getMessage()." : ". json_encode($this->params)."\r\n", FILE_APPEND);
  }
}
