<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyToPrices extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('prices', function(Blueprint $table) {
      $table->string('currency')->default('RUB');
    });

    Schema::table('channel_prices', function(Blueprint $table) {
      $table->string('currency')->default('RUB');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('prices', function(Blueprint $table) {
      $table->dropColumn('currency');
    });

    Schema::table('channel_prices', function(Blueprint $table) {
      $table->dropColumn('currency');
    });
  }
}
