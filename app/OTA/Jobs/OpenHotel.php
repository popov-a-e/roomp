<?php

namespace Roomp\OTA\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

class OpenHotel implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $hotelID;

  public function __construct($hotelID) {
    $this->hotelID = $hotelID;
  }

  public function handle() {
    Artisan::call('sync:availability', ['id' => $this->hotelID]);
    Artisan::call('sync:prices', ['id' => $this->hotelID]);
    Artisan::call('sync:restrictions', ['id' => $this->hotelID]);
  }
}
