<?php

namespace Roomp\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Roomp\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller {
  use AuthenticatesUsers;

  protected $redirectTo = '/';

  public function __construct() {
    $this->middleware('guest', ['except' => 'logout']);
  }

  protected function authenticated(Request $request, $user) {
    return "Login successful";
  }

  protected function guard() {
    return Auth::guard('admins');
  }

  public function username() {
    return 'email';
  }

  public function index() {
    return view('admin.login.index', [
      'translations' => [
        'dates' => __('dates')
      ]
    ]);
  }

  public function logout(Request $request) {
    $this->guard()->logout();

    $request->session()->flush();

    $request->session()->regenerate();

    return redirect()->back();
  }
}
