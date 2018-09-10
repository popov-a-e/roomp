<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmoIdToBd extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('business_developers', function(Blueprint $table) {
      $table->unsignedInteger('amo_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('business_developers', function(Blueprint $table) {
      $table->dropColumn('amo_id');
    });
  }
}
