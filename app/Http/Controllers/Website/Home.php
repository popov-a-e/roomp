<?php

namespace Roomp\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Roomp\City;
use Roomp\Http\Controllers\Controller;


class Home extends Controller {
  private $cities;

  public function __construct(City $city) {
    $this->cities = $city;
  }

  public function index() {
    $params = json_encode([
      'cities' => $this->cities->where('active', true)->get()
    ]);
    return view('website.home.index', compact('params'));
  }

  public function profile() {
    $translations = [
      'profile' => Lang::get('profile')
    ];

    return view('profile.index', compact('translations'));
  }

  public function changeUser(Request $request) {
    $user = Auth::user();

    $user->name = $request->input('name');
    $user->phone = preg_replace('|[^\d]|im', '', $request->input('phone'));
    $user->email = $request->input('email');
    $user->sex_male = $request->input('sex');
    $user->birthday = $request->input('birthday');

    $user->save();

    return 'OK';
  }

  public function changePassword(Request $request) {
    $user = Auth::user();

    if ($request->input('password') !== $request->input('password_confirmation')) {
      abort(401, 'Passwords do not match');
    }

    $user->password = bcrypt($request->input('password'));

    $user->save();

    return __('profile.password_changed');
  }

  public function getReservations() {
    return Auth::user()->reservationsVerbose();
  }

  public function changeLocale($locale) {
    if (Auth::guard('users')->check()) {
      $user = Auth::user();

      $user->locale = $locale;

      $user->save();
    }

    session(['locale' => $locale]);
  }

  public function changeCurrency($currency) {
    if (Auth::guard('users')->check()) {
      $user = Auth::user();

      $user->currency = $currency;

      $user->save();
    }

    session(['currency' => $currency]);
  }
}
