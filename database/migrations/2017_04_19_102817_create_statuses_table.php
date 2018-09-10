<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration {
  public function up() {
    Schema::create('statuses', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');

      $table->boolean('online')->default(false);
      $table->boolean('active')->default(false);
      $table->boolean('confirmed')->default(false);
    });
  }

  public function down() {
    Schema::dropIfExists('statuses');
  }
}
