<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactFacesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('contact_faces', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('organization_id')->index();

      $table->string('name')->nullable();
      $table->string('position')->nullable();
      $table->string('phone')->nullable();
      $table->string('email')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('contact_faces');
  }
}
