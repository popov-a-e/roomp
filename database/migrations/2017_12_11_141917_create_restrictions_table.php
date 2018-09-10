<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestrictionsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('restrictions', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->unsignedInteger('room_id');
      $table->unsignedInteger('rate_id');

      $table->unsignedInteger('hotel_id');

      $table->boolean('closed')->default(false);
      $table->boolean('closed_to_arrival')->default(false);
      $table->boolean('closed_to_departure')->default(false);
      $table->unsignedInteger('minstay')->default(0);
      $table->unsignedInteger('maxstay')->default(0);
      $table->unsignedInteger('minstay_on_arrival')->default(0);

      $table->index('date');
      $table->index('hotel_id');

      $table->index(['room_id', 'rate_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('restrictions');
  }
}
