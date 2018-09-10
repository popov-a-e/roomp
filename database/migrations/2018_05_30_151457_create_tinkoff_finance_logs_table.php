<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTinkoffFinanceLogsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('tinkoff_finance_logs', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('reservation_id')->index();
      $table->string('status');
      $table->unsignedInteger('amount');
      $table->unsignedInteger('payment_id');

      $table->timestamp('timestamp')->default('now()');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('tinkoff_finance_logs');
  }
}
