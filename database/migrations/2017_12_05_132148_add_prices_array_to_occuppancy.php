<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricesArrayToOccuppancy extends Migration
{
  public function up() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->json('prices')->nullable();
      $table->unsignedInteger('price')->nullable(true)->change();
    });
  }

  public function down() {
    Schema::table('occupancy', function(Blueprint $table) {
      $table->dropColumn('prices');
    });
  }
}
