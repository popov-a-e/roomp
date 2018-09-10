<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusLogTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('status_log', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('status_id');
      $table->unsignedInteger('reservation_id');

      $table->unsignedInteger('user_id');
      $table->string('guard')->nullable();

      $table->timestamp('timestamp');

      $table->boolean('active')->default(true);

      $table->index('reservation_id');
      $table->index('status_id');
      $table->index('user_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('status_log');
  }
}
