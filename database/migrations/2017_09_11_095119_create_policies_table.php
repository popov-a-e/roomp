<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliciesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('policies', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('bed_number')->default(0);

      $table->unsignedInteger('price_infants')->nullable();
      $table->unsignedInteger('price_children')->nullable();
      $table->unsignedInteger('price_adults')->nullable();

      $table->unsignedInteger('age_children')->default(12);

      $table->unsignedInteger('hotel_id')->index();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('policies');
  }
}
