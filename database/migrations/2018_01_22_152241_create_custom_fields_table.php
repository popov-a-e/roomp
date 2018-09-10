<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('amocrm__custom_fields', function (Blueprint $table) {
      $table->increments('generic_id');

      $table->unsignedInteger('id')->nullable();//'1968481
      $table->string('name')->nullable();//Город
      $table->unsignedInteger('type_id')->nullable();//'4
      $table->unsignedInteger('account_id')->nullable();//	12390321
      $table->string('description')->nullable();//	null
      $table->string('code')->nullable();//	null
      $table->unsignedInteger('sort')->nullable();//	5
      $table->unsignedInteger('entree_catalog')->nullable();//	0
      $table->boolean('predefined')->nullable();//	false
      $table->string('multiple')->nullable();//N
      $table->boolean('disabled')->default(false);//	false
      $table->unsignedInteger('catalog_id')->nullable();//	null
      $table->unsignedInteger('entree_deals')->nullable();//	1
      $table->unsignedInteger('entree_contacts')->nullable();//	0
      $table->unsignedInteger('entree_company')->nullable();//	0
      $table->unsignedInteger('entree_customers')->nullable();//	0
      $table->string('~name')->nullable();//	Город
      $table->string('required')->nullable();//	false
      $table->boolean('can_be_required')->nullable();//	true
      $table->unsignedInteger('element_type')->nullable();//	2
      $table->boolean('sortable')->nullable();//	true
      $table->boolean('groupable')->nullable();//	true
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('amocrm__custom_fields');
  }
}
