<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TranslateHotel extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */

  public function up() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->renameColumn('name', 'ru');
      $table->string('en')->nullable();
      $table->string('ch')->nullable();

      $table->renameColumn('address', 'address_ru');
      $table->string('address_en')->nullable();
      $table->string('address_ch')->nullable();

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->renameColumn('ru', 'name');
      $table->dropColumn('en');
      $table->dropColumn('ch');

      $table->renameColumn('address_ru', 'address');
      $table->dropColumn('address_en');
      $table->dropColumn('address_ch');
    });
  }
}
