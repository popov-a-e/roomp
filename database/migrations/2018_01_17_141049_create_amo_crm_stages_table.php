<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAMOCrmStagesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('amocrm__stages', function (Blueprint $table) {
      $table->increments('id');

      $table->string('stage_name');
      $table->unsignedInteger('stage_id');
      $table->string('action')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('amocrm__stages');
  }
}
