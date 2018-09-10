<?php

namespace Roomp\Http\Controllers\Admin\Hotels\Channels;

use Illuminate\Support\Facades\Artisan;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;


class SyncController extends Controller {
  public function availability(Hotel $hotel) {
    Artisan::call('sync:availability',['id' => $hotel->id]);
  }

  public function prices(Hotel $hotel) {
    Artisan::call("sync:prices", ['id' => $hotel->id]);
  }

  public function restrictions(Hotel $hotel) {
    Artisan::call("sync:restrictions", ['id' => $hotel->id]);
  }

  public function close(Hotel $hotel) {
    Artisan::call("availability:close", ['id' => $hotel->id]);
  }

  public function tiePrices(Hotel $hotel) {
    Artisan::call("prices:tie", ['id' => $hotel->id]);
  }
}
