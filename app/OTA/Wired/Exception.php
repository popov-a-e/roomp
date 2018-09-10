<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 14.06.2018
 * Time: 13:40
 */

namespace Roomp\OTA\Wired;

use Exception as BaseException;
use Throwable;

class Exception extends BaseException {
  public function __construct($message = "", $code = 0, Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}