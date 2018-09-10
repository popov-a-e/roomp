<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookRatesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('wubook__rates', function (Blueprint $table) {
      $table->increments('id');

      $table->string('booking_name')->default('');
      $table->unsignedInteger('chid')->index();
      $table->unsignedInteger('wubook_rate_id')->nullable();
      $table->unsignedInteger('booking_rate_id')->index();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('wubook__rates');
  }
}
