<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 23.04.2017
 * Time: 14:03
 */

namespace Roomp\Drivers\Tinkoff;

use Carbon\Carbon;

class Repository {
  public function pushStatus(string $status, int $reservationID, int $amount, int $paymentID) {
    $payment = new FinanceLog();

    $payment->status = $status;
    $payment->reservation_id = $reservationID;
    $payment->amount = $amount * 100;
    $payment->payment_id = $paymentID;
    $payment->timestamp = Carbon::now();

    $payment->save();

    if (in_array($status, ['CONFIRMED', 'REFUNDED', 'PARTIALLY_REFUNDED'])) $payment->reservation->updatePaymentStatus();

    return $payment;
  }
}