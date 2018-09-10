<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatePlanIdToWubookRates extends Migration {
  public function up() {
    Schema::table('wubook__rates', function (Blueprint $table) {
      $table->unsignedInteger('wubook_rate_plan_id')->nullable();
    });
  }

  public function down() {
    Schema::table('wubook__rates', function (Blueprint $table) {
      $table->dropColumn('wubook_rate_plan_id');
    });
  }
}
