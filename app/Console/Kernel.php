<?php

namespace Roomp\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
  protected $commands = [
  ];

  protected function schedule(Schedule $schedule) {
//    $schedule->command('reservations:check')->everyMinute();
//
//    $schedule->command('send_noshow_reminders')->dailyAt('03:00');
//    $schedule->command('reservations:fulfill')->dailyAt('12:00');
//
//    $schedule->command('sync:all')->twiceDaily();
//
//    if (!env('APP_IS_BETA')) $schedule->command('currencies:update')->hourly();
//
//    $schedule->command('check_wubook_connections')->cron('0 */3 * * *');
//

  }

  protected function commands() {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
