<?php

namespace Roomp\Hotels\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Roomp\Hotels\Hotel;
use Roomp\Mail\Extranet\NoShowReminder as ReminderMail;

class NoShowReminder extends Notification {
  use Queueable;

  private $reservations;

  public function __construct($reservations) {
    $this->reservations = $reservations;
  }

  public function via($hotel) {
    return ['mail'];
  }

  public function toMail($hotel) {
    return (new ReminderMail($hotel, $this->reservations))->to($hotel->receptionEmails);
  }

  public function toArray($hotel) {
    return [
      //
    ];
  }
}
