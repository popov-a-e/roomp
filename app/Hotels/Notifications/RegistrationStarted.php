<?php

namespace Roomp\Hotels\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Roomp\Hotels\Hotel;
use Roomp\Mail\Extranet\Registration;

class RegistrationStarted extends Notification {
  use Queueable;

  private $hotel;

  public function __construct(Hotel $hotel) {
    $this->hotel = $hotel;
  }

  public function via($notifiable) {
    return ['mail'];
  }

  public function toMail($hotel) {
    return (new Registration($hotel->id, $hotel->generateInviteToken()))->to($hotel->receptionEmails);
  }

  public function toArray($notifiable) {
    return [
      //
    ];
  }
}
