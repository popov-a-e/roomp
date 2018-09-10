<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBusinessDeveloperHotelsToAmocrmLeads extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('business_developer_hotels', function(Blueprint $table) {
      $table->rename('amocrm__leads');

      $table->string('lead_name')->default('');
      $table->unsignedInteger('business_developer_id')->nullable();
      $table->unsignedInteger('last_stage_id')->nullable();
      $table->unsignedInteger('hotel_id')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('amocrm__leads', function(Blueprint $table) {
      $table->dropColumn('lead_name', 'business_developer_id', 'last_stage_id');

      $table->rename('business_developer_hotels');
    });
  }
}
