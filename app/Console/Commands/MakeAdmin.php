<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Roomp\Admin;

class MakeAdmin extends Command {
  protected $signature = 'make:admin {name} {email} {password}';

  protected $description = 'Creating new admin';

  public function __construct() {
    parent::__construct();
  }

  public function handle() {
    $admin = new Admin;

    $admin->name = $this->argument('name');
    $admin->email = $this->argument('email');
    $admin->password = bcrypt($this->argument('password'));

    $admin->save();
  }
}
