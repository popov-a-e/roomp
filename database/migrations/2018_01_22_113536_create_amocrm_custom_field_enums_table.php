<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAMOCrmCustomFieldEnumsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('amocrm__custom_field_enums', function (Blueprint $table) {
      $table->increments('id');

      $table->string('custom_field_id');
      $table->string('name')->nullable();
      $table->unsignedInteger('sort')->default(0);
      $table->unsignedInteger('roomp_id');
      $table->unsignedInteger('amocrm_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('amocrm__custom_field_enums');
  }
}
