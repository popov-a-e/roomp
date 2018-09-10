<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOstrovokUuisToId extends Migration {
  public function up() {
    Schema::table('ostrovok__reservations', function (Blueprint $table) {
      $table->renameColumn('uuid', 'ostrovok_id');

    });
  }

  public function down() {
    Schema::table('ostrovok__reservations', function (Blueprint $table) {
      $table->renameColumn('ostrovok_id', 'uuid');
    });
  }
}
