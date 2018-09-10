<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeJobsTable extends Migration {
  public function up() {
    Schema::table('jobs', function (Blueprint $table) {
      $table->string('connection')->nullable();
      $table->text('exception')->nullable();
      $table->timestamp('failed_at')->nullable();
      $table->unsignedInteger('attempts')->default(0)->change();
      $table->unsignedInteger('available_at')->nullable()->change();
      $table->unsignedInteger('created_at')->nullable()->change();
    });
  }

  public function down() {
    Schema::table('jobs', function(Blueprint $table) {
      $table->dropColumn('connection', 'failed_at', 'exception');
    });
  }
}
