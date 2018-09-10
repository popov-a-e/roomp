<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('eval {str}', function($str) {
  eval("dd($str);");
});

Artisan::command('get {hotel}', function(Roomp\Hotels\Hotel $hotel) {
  dd ($hotel);
});