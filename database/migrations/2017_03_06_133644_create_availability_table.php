<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilityTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('availability', function (Blueprint $table) {
      $table->increments('id');

      $table->date('date');

      $table->unsignedInteger('room_id');

      $table->unsignedInteger('hotel_id');

      $table->unsignedInteger('available');

      $table->index('date');
      $table->index('room_id');
      $table->index('hotel_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('availability');
  }
}
