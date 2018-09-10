<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class OldHotelRegistrationOffer extends Mailable {
  use Queueable, SerializesModels;

  public $id, $hotel;
  public $token;
  public $subdomain;

  public function __construct($id, $token) {
    $this->id = $id;
    $this->token = $token;
    $repository = app()->make(HotelRepository::class);
    $this->hotel = $repository->find($id);

    $this->subject = 'Roomp - электронная оферта';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    return $this->from('partners@roomp.co', 'Roomp - партнерская сеть отелей')
      ->attach(base_path('requisites.pdf'), [
        'as' => 'Реквизиты Roomp.pdf',
        'mime' => 'application/pdf',
      ])
      ->markdown('email.hotelier.old_hotel');
  }
}
