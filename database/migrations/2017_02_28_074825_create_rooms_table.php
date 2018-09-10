<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('rooms', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');

      $table->unsignedInteger('hotel_id');
      $table->unsignedInteger('room_class_id');

      $table->unsignedInteger('number');
      $table->unsignedInteger('max_guest_number')->default(2);

      $table->unsignedInteger('size')->nullable();

      $table->text('photos');

      $table->timestamps();

      $table->index('hotel_id');
      $table->index('room_class_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('rooms');
  }
}
