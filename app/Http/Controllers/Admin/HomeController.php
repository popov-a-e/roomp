<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Roomp\City;
use Roomp\Hotels\HotelAmenity;
use Roomp\Hotels\Rooms\Allotment;
use Roomp\Hotels\Rooms\RoomAmenity;
use Roomp\Hotels\Rooms\RoomClass;
use Roomp\Http\Controllers\Controller;


class HomeController extends Controller {
  private $repository;

  public function __construct() {
    $this->middleware(function($request, $next) {
      view()->share([
        'user' => auth('admins')->user()
      ]);


      return $next($request);
    });
  }

  public function index() {
    $user = Auth::guard('admins')->user();

    return view('admin.index.index');
  }

  public function getMeta() {
    return [
      'allotments' => Allotment::all(),
      'room_classes' => RoomClass::all(),
      'room_amenities' => RoomAmenity::all(),
      'hotel_amenities' => HotelAmenity::all(),
      'cities' => City::all(),
      'languages' => [ // TODO
        ['id' => 'ru', 'name' => 'Русский'],
        ['id' => 'en', 'name' => 'English']
      ],
      'channels' => ['Roomp', 'Booking'],
      'countries' => ['ru'],
      'currencies' => config('app.currencies')
    ];
  }

  public function getList($type) {
    return $this->repository->getList($type);
  }
}
