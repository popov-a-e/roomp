<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookOccupanciesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('wubook__occupancies', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('lcode');
      $table->unsignedInteger('occupancy');
      $table->unsignedInteger('rid');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('wubook__occupancies');
  }
}
