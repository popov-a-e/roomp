<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class HotelierRegistrationOffer extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $id, $hotel;
  public $token;
  public $subdomain;

  public function __construct($id, $token) {
    $this->id = $id;
    $this->token = $token;
    $repository = app()->make(HotelRepository::class);
    $this->hotel = $repository->find($id);

    $this->subject = 'Регистрация в Roomp';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    return $this->from('partners@roomp.co', 'Roomp - партнерская сеть отелей')->markdown('email.hotelier.registration_offer');
  }
}
