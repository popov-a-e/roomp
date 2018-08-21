<?php

namespace Roomp\Http;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Roomp\Http\Middleware\CheckForMaintenanceMode;
use Roomp\Http\Middleware\EncryptCookies;
use Roomp\Http\Middleware\RedirectIfAuthenticated;
use Roomp\Http\Middleware\TrimStrings;
use Roomp\Http\Middleware\TrustProxies;
use Roomp\Http\Middleware\VerifyCsrfToken;

class Kernel extends HttpKernel {
  protected $middleware = [
    CheckForMaintenanceMode::class,
    ValidatePostSize::class,
    TrimStrings::class,
    ConvertEmptyStringsToNull::class,
    TrustProxies::class,
  ];

  protected $middlewareGroups = [
    'web' => [
      EncryptCookies::class,
      AddQueuedCookiesToResponse::class,
      StartSession::class,
      // \Illuminate\Session\Middleware\AuthenticateSession::class,
      ShareErrorsFromSession::class,
      VerifyCsrfToken::class,
      SubstituteBindings::class,
    ],

    'api' => [
      'throttle:3600,1',
      'bindings',
    ],
  ];

  protected $routeMiddleware = [
    'auth' => Authenticate::class,
    'auth.basic' => AuthenticateWithBasicAuth::class,
    'bindings' => SubstituteBindings::class,
    'cache.headers' => SetCacheHeaders::class,
    'can' => Authorize::class,
    'guest' => RedirectIfAuthenticated::class,
    'signed' => ValidateSignature::class,
    'throttle' => ThrottleRequests::class,
  ];
}
