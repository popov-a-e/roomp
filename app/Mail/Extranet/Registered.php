<?php

namespace Roomp\Mail\Extranet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class Registered extends Mailable /*implements ShouldQueue*/ {
  use Queueable, SerializesModels;

  public $hotel, $subdomain, $email, $password, $channel_manager, $isNewAccount;

  public function __construct($hotelID, $email, $password) {
    $repository = app()->make(HotelRepository::class);
    $this->hotel = $repository->find($hotelID);

    $this->isNewAccount = $this->hotel->hotelier->hotels()->whereHas('offer', function ($q) {
        return $q->whereNotNull('accept_date');
      })->count() === 1;

    $this->email = $email;
    $this->password = $password;
    $this->subject = __('extranet.mail.subject.registered', [], $this->hotel->hotelier->locale);//'Подключение к Roomp';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
    $this->channel_manager = $this->hotel->channel;
  }

  public function build() {
    $this->locale = $this->hotel->hotelier->locale;
    return $this->locale($this->hotel->hotelier->locale)->from('partners@roomp.co', __('extranet.mail.from.partners@roomp', [], $this->locale))
      ->withSwiftMessage(function($message) {
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-Mailgun-Track-Clicks', 'no');
      })
      ->markdown("email.extranet.{$this->locale}.registered");
  }
}
