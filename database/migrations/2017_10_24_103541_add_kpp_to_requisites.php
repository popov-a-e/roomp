<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKppToRequisites extends Migration {
  public function up() {
    Schema::table('hotel_requisites', function (Blueprint $table) {
      $table->string('KPP')->default('');
    });
  }

  public function down() {
    Schema::table('hotel_requisites', function (Blueprint $table) {
      $table->dropColumn('KPP');
    });
  }
}
