<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeactivatedToPromoCode extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('promo_codes', function (Blueprint $table) {
      $table->boolean('deactivated')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('promo_codes', function (Blueprint $table) {
      $table->dropColumn('deactivated');
    });
  }
}
