<?php

namespace Roomp\Mail\Extranet;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Roomp\Hotels\Hotel;
use Roomp\Repositories\HotelRepository;

class NoShowReminder extends Mailable /*implements ShouldQueue*/ {
  use Queueable, SerializesModels;

  public $reservations;
  public $token;
  public $hotelID;
  public $hotelierLocale;

  public function __construct(Hotel $hotel, $reservations) {
    $this->reservations = $reservations;

    $this->token = str_random(128);
    $this->hotelID = $hotel->id;
    $this->hotelierLocale = $hotel->hotelier->locale;

    Redis::set("access_token:$this->hotelID", $this->token);

    $this->subject = __('extranet.mail.subject.noshow_reminder', [], $this->locale);
  }

  public function build() {
    return $this
      ->locale($this->hotelierLocale)
      ->from('partners@roomp.co', __('extranet.mail.from.partners@roomp', [], $this->locale))
      ->markdown("email.extranet.{$this->hotelierLocale}.noshow_reminder");
  }
}
