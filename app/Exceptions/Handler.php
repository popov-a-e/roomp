<?php

namespace Roomp\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
  protected $dontReport = [
    //
  ];

  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  public function report(Exception $exception) {
    parent::report($exception);
  }

  public function render($request, Exception $exception) {
    return parent::render($request, $exception);
  }

  protected function unauthenticated($request, AuthenticationException $exception) {
    return $request->expectsJson()
      ? response()->json(['message' => $exception->getMessage()], 401)
      : redirect()->guest('/login');
  }
}
