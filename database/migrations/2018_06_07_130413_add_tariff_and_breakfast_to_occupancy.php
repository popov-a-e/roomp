<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTariffAndBreakfastToOccupancy extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->string('tariff')->nullable();
      $table->boolean('breakfast_included')->default(0);
      $table->boolean('is_nr')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->dropColumn('tariff', 'breakfast_included');
    });
  }
}
