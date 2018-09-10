<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Roomp\Reservation;

class ReservationMail extends Mailable {
  use Queueable, SerializesModels;

  public $reservation;
  public $status;
  public $occupancies;
  public $creator;
  public $hotel;
  public $nights;
  public $total;
  public $photo;
  public $userLocale;

  public function __construct(Reservation $reservation) {
    $reservationData = $reservation->verbose();
    $this->userLocale = $reservation->creator->locale ?? 'en';

    foreach($reservationData as $key => $value) {
      $this->$key = $value;
    }

    $this->subject = __("reservation.notification", ["code" => $reservation->code]);
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->locale($this->userLocale)->markdown('email.reservation.index');
  }
}
