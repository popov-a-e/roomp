<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccupancyTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('occupancy', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('room_id');

      $table->unsignedInteger('reservation_id');

      $table->unsignedInteger('allotment_id');

      $table->unsignedInteger('guest_number');
      $table->unsignedInteger('price');

      $table->index('reservation_id');
      $table->index('room_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('occupancy');
  }
}
