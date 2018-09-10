<?php

namespace Roomp\Mail\Extranet;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class Registration extends Mailable /*implements ShouldQueue*/ {
  use Queueable, SerializesModels;

  public $id, $hotel, $isNewAccount;
  public $token;
  public $subdomain;

  public function __construct($id, $token) {
    $this->id = $id;
    $this->token = $token;
    $repository = app()->make(HotelRepository::class);
    $this->hotel = $repository->find($id);

    $this->isNewAccount = !$this->hotel->hotelier->email;
    $this->subject = __('extranet.mail.subject.registration', [], $this->hotel->hotelier->locale);
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    return $this
      ->locale($this->hotel->hotelier->locale)
      ->from('partners@roomp.co', __('extranet.mail.from.partners@roomp', [], $this->hotel->hotelier->locale))
      ->withSwiftMessage(function($message) {
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-Mailgun-Track-Clicks', 'no');
      })
      ->markdown("email.extranet.{$this->hotel->hotelier->locale}.registration");
  }
}
