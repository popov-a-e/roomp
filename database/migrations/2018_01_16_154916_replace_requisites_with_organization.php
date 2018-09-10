<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceRequisitesWithOrganization extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotels', function(Blueprint $table) {
      $table->renameColumn('requisite_id', 'organization_id');
    });

    Schema::table('hotel_requisites', function (Blueprint $table) {
      $table->rename('organizations');

      $table->string('form')->nullable()->change();
      $table->string('name')->nullable()->change();
      $table->string('CEO')->nullable()->change();
      $table->string('INN')->nullable()->change();
      $table->string('OGRN')->nullable()->change();
      $table->string('OKPO')->nullable()->change();
      $table->string('account')->nullable()->change();
      $table->string('bank')->nullable()->change();
      $table->string('BIC')->nullable()->change();

      $table->string('corr_account')->nullable()->change();
      $table->text('legal_address')->nullable()->change();
      $table->text('fact_address')->nullable()->change();
      $table->text('post_address')->nullable()->change();

      $table->string('phone')->nullable()->change();
      $table->string('email')->nullable()->change();

      $table->string('contract_n')->nullable()->change();
      $table->string('contract_date')->nullable()->change();
      $table->string('contract_signee')->nullable()->change();
      $table->string('contract_scan')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {

    Schema::table('hotels', function(Blueprint $table) {
      $table->renameColumn('organization_id', 'requisite_id');
    });

    Schema::table('organizations', function (Blueprint $table) {
      $table->rename('hotel_requisites');
    });
  }
}
