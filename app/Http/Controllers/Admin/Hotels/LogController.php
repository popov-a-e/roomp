<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller {
  public function show(Hotel $hotel) {
    return $hotel->logs;
  }

  public function store(Hotel $hotel, Request $request) {
    $hotel->logs()->create([
      'message' => $request->input('message'),
      'admin_id' => auth('admins')->id()
    ]);
  }
}
