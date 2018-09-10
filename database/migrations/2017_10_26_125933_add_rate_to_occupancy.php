<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateToOccupancy extends Migration {
  public function up() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->json('rates')->nullable();
    });
  }

  public function down() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->dropColumn('rates');
    });
  }
}
