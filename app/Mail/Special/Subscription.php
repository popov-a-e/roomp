<?php

namespace Roomp\Mail\Special;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Subscription extends Mailable /*implements ShouldQueue*/ {
  use Queueable, SerializesModels;

  public function __construct() {
    $this->subject = 'Добро пожаловать в Экстранет Roomp';
  }

  public function build() {
    return $this->from('partners@roomp.co', 'Roomp')->markdown('email.extranet.special.subscription');
  }
}
