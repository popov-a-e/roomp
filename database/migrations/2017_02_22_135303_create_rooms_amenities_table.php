<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsAmenitiesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('rooms_amenities', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('room_id');
      $table->unsignedInteger('amenity_id');

      $table->index('room_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('rooms_amenities');
  }
}
