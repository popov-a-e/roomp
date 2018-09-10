<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceLogTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('finance_log', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('reservation_id');
      $table->string('order_id');

      $table->string('action');
      $table->string('status');

      $table->unsignedInteger('amount');

      $table->timestamp('timestamp');

      $table->index('reservation_id', 'actor_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('finance_log');
  }
}
