<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRateFromWubookConnections extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__connections', function (Blueprint $table) {
      $table->dropColumn('rate_id', 'rate_assigned');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__connections', function (Blueprint $table) {
      $table->unsignedInteger('rate_id')->nullable();
      $table->boolean('rate_assigned')->default(false);
    });
  }
}
