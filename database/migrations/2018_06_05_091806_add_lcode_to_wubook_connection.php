<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLcodeTowubookConnection extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__connections', function (Blueprint $table) {
      $table->unsignedInteger('lcode')->default(1501655009);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__connections', function (Blueprint $table) {
      $table->dropColumn('lcode');
    });
  }
}
