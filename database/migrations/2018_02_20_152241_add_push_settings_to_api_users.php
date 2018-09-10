<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPushSettingsToApiUsers extends Migration {
  public function up() {
    Schema::table('api_users', function(Blueprint $table) {
      $table->string('push_endpoint')->nullable();
      $table->string('push_login')->nullable();
      $table->string('push_password')->nullable();
    });
  }

  public function down() {
    Schema::table('api_users', function(Blueprint $table) {
      $table->dropColumn('push_endpoint');
      $table->dropColumn('push_login');
      $table->dropColumn('push_password');
    });
  }
}
