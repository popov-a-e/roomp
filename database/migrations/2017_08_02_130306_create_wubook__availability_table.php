<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookAvailabilityTable extends Migration
{
  public function up() {
    Schema::create('wubook__availability', function (Blueprint $table) {
      $table->increments('id');

      $table->date('date')->index();

      $table->unsignedInteger('room_id')->index();

      $table->unsignedInteger('hotel_id')->index();

      $table->unsignedInteger('available');
    });
  }

  public function down() {
    Schema::dropIfExists('wubook__availability');
  }
}
