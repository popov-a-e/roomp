<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRateTables extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__rates', function (Blueprint $table) {
      $table->rename('wubook__rate_mappings');
    });

    Schema::table('wubook__occupancies', function (Blueprint $table) {
      $table->rename('wubook__rates');
      $table->unsignedInteger('rate_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__rates', function (Blueprint $table) {
      $table->dropColumn('rate_id');
      $table->rename('wubook__occupancies');
    });

    Schema::table('wubook__rate_mappings', function (Blueprint $table) {
      $table->rename('wubook__rates');
    });
  }
}
