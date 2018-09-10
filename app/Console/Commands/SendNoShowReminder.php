<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Roomp\CRS\Reservation;
use Roomp\Hotels\Hotel;
use Roomp\Mail\Extranet\NoShowReminder;

class SendNoShowReminder extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'send_noshow_reminders {hotel_id?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '';

  private $reservations, $hotel;

  public function __construct(Reservation $reservation, Hotel $hotel) {
    parent::__construct();
    $this->reservations = $reservation;
    $this->hotels = $hotel;
  }

  public function handle() {
    $hotelID = $this->argument('hotel_id');

    if ($hotelID) $this->hotels = $this->hotels->where('id', $hotelID);

    $this->hotels->with(['reservations' => function($reservations) {
      return $reservations->with('statusLog')->unconfirmed();
    }])->get()->each->remindOfNoShows();
  }
}
