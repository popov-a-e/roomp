<?php

namespace Roomp\Drivers\Tinkoff;

use Illuminate\Database\Eloquent\Model;
use Roomp\Reservation;

class FinanceLog extends Model {
  protected $table = 'tinkoff_finance_logs';
  public $timestamps = false;

  public function reservation() {
    return $this->belongsTo(Reservation::class);
  }
}
