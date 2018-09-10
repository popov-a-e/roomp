<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTableHotels extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotels', function(Blueprint $table) {
      $table->string('email_confirmed')->default('1');
      $table->string('password_confirmed')->default('1');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotels', function(Blueprint $table) {
      $table->dropColumn('email_confirmed');
      $table->dropColumn('password_confirmed');
    });
  }
}
