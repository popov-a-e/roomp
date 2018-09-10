<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FloatizePrices extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('prices', function(Blueprint $table) {
      $table->float('price')->change();
    });
  }

  public function down() {
    Schema::table('prices', function(Blueprint $table) {
      $table->unsignedInteger('price')->change();
    });
  }
}
