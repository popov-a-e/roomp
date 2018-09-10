<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Roomp\Reservation;

class ReservationHotelierCancelMail extends Mailable {
  use Queueable, SerializesModels;

  public $reservation;
  public $status;
  public $occupancies;
  public $creator;
  public $hotel;
  public $nights;
  public $total;
  public $photo;
  public $guests;
  public $hotelierLocale;

  public function __construct(Reservation $reservation) {
    $reservationData = $reservation->verbose();
    $this->hotelierLocale = $reservation->hotel->hotelier->locale;

    foreach($reservationData as $key => $value) {
      $this->$key = $value;
    }

    $this->guests = $this->occupancies->reduce(function($p, $o) {return $p + $o->adults + $o->children + $o->infants; }, 0);

    $this->subject = __("reservation.notification_cancelled", ["code" => $reservation->code]);
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->locale($this->hotelierLocale)->markdown('email.reservation.hotelier_cancel');
  }
}
