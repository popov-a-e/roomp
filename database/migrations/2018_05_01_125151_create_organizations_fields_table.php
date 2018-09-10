<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsFieldsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('organizations_fields', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('organization_id')->index();
      $table->unsignedInteger('field_id')->index();
      $table->string('field_value', 2048)->nullable();
    });

    Schema::table('organizations', function(Blueprint $table) {
      $table->string('locale')->default('ru');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('organizations_fields');

    Schema::table('organizations', function(Blueprint $table) {
      $table->dropColumn('locale');
    });
  }
}
