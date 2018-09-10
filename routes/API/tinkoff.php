<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('tinkoff/notification', function(Request $request) {
  $roomp = \Roomp\Channel::where('name', 'Roomp')->first();
  if ($roomp) auth('channels')->login($roomp);
  \Roomp\Facades\Tinkoff::pushStatus((object)$request->all());
});