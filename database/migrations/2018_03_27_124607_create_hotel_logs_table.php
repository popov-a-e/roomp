<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelLogsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hotel_logs', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id')->index();
      $table->foreign('hotel_id')
        ->references('id')->on('hotels')
        ->onDelete('cascade');

      $table->unsignedInteger('admin_id');
      $table->text('message')->default('');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hotel_logs');
  }
}
