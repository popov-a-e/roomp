<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\OTA\Wired\Repository;
use Roomp\OTA\Wired\Driver;

class CheckWubookReservations extends Command {
  protected $signature = 'reservations:wubook';
  protected $description = 'Checking wubook reservations';

  private $repository;
  private $driver;

  public function __construct(Driver $driver, Repository $repository) {
    parent::__construct();
    $this->driver = $driver;
    $this->repository = $repository;
  }

  public function handle() {
    $hotels = $this->repository->getHotels();

    $hotels->each(function ($hotel) {
      $reservations = $this->driver->getNewReservations($hotel->lcode);

      foreach($reservations as $reservation) {
        $this->repository->handleReservation($hotel->lcode, $reservation->reservation_code);
      };
    });
  }
}
