<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */

  public function up() {
    Schema::create('cities', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->unique();
      $table->double('lat');
      $table->double('lng');
      $table->float('utc_offset');

      $table->boolean('active')->default(true);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('cities');
  }
}
