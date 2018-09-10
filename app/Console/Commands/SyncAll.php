<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Roomp\Hotels\Hotel;

class SyncAll extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sync:all';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sync all data for all active hotels';

  private $hotels;

  public function __construct(Hotel $hotel) {
    parent::__construct();

    $this->hotels = $hotel;
  }

  public function handle() {
    $this->hotels->where('disabled', false)->pluck('id')->each(function ($id) {
      Artisan::call('sync:availability', ['id' => $id]);
      Artisan::call('sync:prices', ['id' => $id]);
      Artisan::call('sync:restrictions', ['id' => $id]);

      echo "$id ";

      sleep(10);
    });
  }
}
