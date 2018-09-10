<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\CRS\Reservation;

class FulfillReservations extends Command {
  protected $signature = 'reservations:fulfill';

  private $reservations;

  public function __construct(Reservation $reservation) {
    parent::__construct();

    $this->reservations = $reservation;
  }

  protected $description = 'Runs fulfilled hook on reservations';

  public function handle() {
    $this->reservations->unfulfilled()->get()->each->fulfill();
  }
}
