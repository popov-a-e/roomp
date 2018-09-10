<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstrovokRoomsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('ostrovok__rooms', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');

      $table->unsignedInteger('ostrovok_id');
      $table->unsignedInteger('ostrovok_hotel_id');
      $table->unsignedInteger('room_id')->nullable();

      $table->index('ostrovok_hotel_id');
      $table->index('ostrovok_id');
      $table->index('room_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('ostrovok__rooms');
  }
}
