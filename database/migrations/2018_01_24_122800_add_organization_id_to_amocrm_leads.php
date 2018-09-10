<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganizationIdToAmocrmLeads extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('amocrm__leads', function(Blueprint $table) {
      $table->unsignedInteger('organization_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('amocrm__leads', function(Blueprint $table) {
      $table->dropColumn('organization_id');
    });
  }
}
