<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmocrmIdToContactFaces extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('contact_faces', function (Blueprint $table) {
      $table->unsignedInteger('amocrm_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('contact_faces', function (Blueprint $table) {
      $table->dropColumn('amocrm_id');
    });
  }
}
