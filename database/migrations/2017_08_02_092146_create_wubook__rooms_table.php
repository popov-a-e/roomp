<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up() {
    Schema::create('wubook__rooms', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('wubook_hotel_id')->index();
      $table->unsignedInteger('rid')->index();
      $table->unsignedInteger('room_id')->index()->nullable();
    });
  }

  public function down() {
    Schema::dropIfExists('wubook__rooms');
  }
}
