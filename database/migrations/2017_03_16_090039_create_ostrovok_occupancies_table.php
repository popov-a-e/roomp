<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstrovokOccupanciesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('ostrovok__occupancies', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('ostrovok_id');
      $table->unsignedInteger('capacity');
      $table->unsignedInteger('ostrovok_room_id');

      $table->index('ostrovok_room_id');
      $table->index('ostrovok_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('ostrovok__occupancies');
  }
}
