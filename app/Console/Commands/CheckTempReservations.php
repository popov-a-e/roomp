<?php

namespace Roomp\Console\Commands;
use Illuminate\Console\Command;
use Roomp\CRS\Reservation;

class CheckTempReservations extends Command {
  protected $signature = 'reservations:check';
  private $reservations;

  public function __construct(Reservation $reservation) {
    parent::__construct();
    
    $this->reservations = $reservation;
  }

  public function handle () {
    $this->reservations->overdue()->get()->each->unconfirm();
  }
}