<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToOrganizationFields extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('organization_fields', function(Blueprint $table) {
      $table->unsignedInteger('order')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('organization_fields', function(Blueprint $table) {
      $table->dropColumn('order');
    });
  }
}
