<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToOrganizationFields extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('organization_fields', function (Blueprint $table) {
      $table->boolean('is_bank_field')->default('false');
      $table->boolean('readonly')->default('true');
      $table->boolean('is_public')->default('true');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('organization_fields', function (Blueprint $table) {
      $table->dropColumn('is_bank_field');
      $table->dropColumn('readonly');
      $table->dropColumn('is_public');
    });
  }
}
