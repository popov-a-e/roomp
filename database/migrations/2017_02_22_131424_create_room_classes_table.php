<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up() {
    Schema::create('room_classes', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->unique();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('room_classes');
  }
}
