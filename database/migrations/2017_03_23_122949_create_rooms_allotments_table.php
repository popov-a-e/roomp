<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsAllotmentsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('rooms_allotments', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('room_id');
      $table->unsignedInteger('allotment_id');

      $table->index('room_id');
      $table->index('allotment_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('rooms_allotments');
  }
}
