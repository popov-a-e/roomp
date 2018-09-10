<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookConnectionMappingsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('wubook__connection_mappings', function (Blueprint $table) {
      $table->increments('id');

      $table->string('booking_name')->default('');
      $table->unsignedInteger('chid')->index();
      $table->unsignedInteger('wubook_room_id')->nullable();
      $table->unsignedInteger('booking_room_id')->index();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('wubook__connection_mappings');
  }
}
