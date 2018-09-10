<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookedTable extends Migration
{
  public function up() {
    Schema::create('booked', function (Blueprint $table) {
      $table->increments('id');

      $table->date('date')->index();

      $table->unsignedInteger('room_id')->index();

      $table->unsignedInteger('hotel_id')->index();

      $table->unsignedInteger('booked')->default(0);
    });
  }

  public function down() {
    Schema::dropIfExists('booked');
  }
}
