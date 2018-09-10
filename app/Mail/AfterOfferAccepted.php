<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class AfterOfferAccepted extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $hotel;

  public function __construct($hotelID) {
    $repository = app()->make(HotelRepository::class);
    $this->hotel = $repository->find($hotelID);

    $this->subject = 'Подключение к Roomp';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    $attach_instruction = $this->hotel->channel_id === 1;

    $file = $this
      ->from('partners@roomp.co', 'Roomp - партнерская сеть отелей');

    if ($attach_instruction) {
      $file->attach(base_path('instruction.pdf'), [
        'as' => 'Инструкция.pdf',
        'mime' => 'application/pdf',
      ]);
    }

    return $file->attach(base_path('memo.pdf'), [
      'as' => 'Памятка.pdf',
      'mime' => 'application/pdf',
    ])->markdown($attach_instruction ? 'email.hotelier.after_registration' : 'email.hotelier.after_registration_no_cm');
  }
}
