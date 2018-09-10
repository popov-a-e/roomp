<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoompRatesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('roomp_rates', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('season_id')->index();
      $table->unsignedInteger('room_class_id')->index();
      $table->float('rate');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('roomp_rates');
  }
}
