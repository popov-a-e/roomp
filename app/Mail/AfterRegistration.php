<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AfterRegistration extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $email;
  public $password;
  public $subdomain;

  public function __construct() {

    $this->email = 'simon@rtrtr/rtrt';
    $this->password = 'dhfsdjfhksdjfjrt';

    $this->subject = 'Данные для входа в Roomp';
    $this->subdomain = env('APP_IS_BETA') ? 'draftextranet' : 'extranet';
  }

  public function build() {
    return $this->from('partners@roomp.co', 'Roomp - партнерская сеть отелей')->markdown('email.hotelier.ru.registration');
  }
}
