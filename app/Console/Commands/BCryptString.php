<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;

class BCryptString extends Command {
  protected $signature = 'bcrypt {str}';

  protected $description = 'Return Bcrypt of the given string';

  public function handle() {
    $this->info(bcrypt($this->argument('str')));
  }
}
