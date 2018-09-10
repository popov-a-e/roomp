<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Roomp\Reservation;

class ReservationHotelierMail extends Mailable {
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
  public $phone;

  public function __construct(Reservation $reservation) {
    $this->hotelierLocale = $reservation->hotel->hotelier->locale;
    $reservationData = $reservation->verbose();

    foreach($reservationData as $key => $value) {
      $this->$key = $value;
    }

    $this->guests = $this->occupancies->reduce(function($p, $o) {return $p + $o->adults + $o->children + $o->infants; }, 0);
    $this->phone = $reservation->creator ? $reservation->creator->phone : null;

    $this->subject = __("reservation.notification", ["code" => $reservation->code]);
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->locale($this->hotelierLocale)->markdown('email.reservation.hotelier');
  }
}
