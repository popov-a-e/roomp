<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstrovokReservationsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('ostrovok__reservations', function (Blueprint $table) {
      $table->increments('id');

      $table->string('uuid')->index();
      $table->unsignedInteger('reservation_id')->index();
      $table->unsignedInteger('ostrovok_hotel_id');
      $table->string('action')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('ostrovok__reservations');
  }
}
