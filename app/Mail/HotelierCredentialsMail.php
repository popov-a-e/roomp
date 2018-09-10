<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Roomp\Repositories\HotelRepository;

class HotelierCredentialsMail extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $email, $password, $subdomain;

  public function __construct($email, $password) {
    $this->email = $email;
    $this->password = $password;

    $this->subject = 'Данные для входа в Roomp';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    return $this->from('partners@roomp.co', 'Roomp - партнерская сеть отелей')->markdown('email.hotelier.ru.registration');
  }
}
