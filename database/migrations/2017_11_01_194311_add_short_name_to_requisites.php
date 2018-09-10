<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortNameToRequisites extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotel_requisites', function(Blueprint $table) {
      $table->string('CEO_short_name')->default('');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotel_requisites', function(Blueprint $table) {
      $table->dropColumn('CEO_short_name');
    });
  }
}
