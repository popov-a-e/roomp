<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Roomp\Drivers\DataProviders\Currency;

class UpdateCurrencies extends Command {
  protected $signature = 'currencies:update';

  protected $description = '';

  private $currency;

  public function __construct(Currency $currency) {
    parent::__construct();
    $this->currency = $currency;
  }

  public function handle() {
    $currencies = config('app.currencies');//['RUB', 'USD', 'GEL'];

    foreach ($currencies as $indexFrom => $from) {
      foreach ($currencies as $indexTo => $to) {
        if ($from === $to || $indexTo < $indexFrom) continue;

        $ratio = $this->currency->getCurrencyRatio($from, $to);

        Redis::set($from.':'.$to, $ratio[1]);
        Redis::set($to.':'.$from, $ratio[0]);
      }
    }
  }
}
