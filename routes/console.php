<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('eval {str}', function($str) {
  eval("dd($str);");
});