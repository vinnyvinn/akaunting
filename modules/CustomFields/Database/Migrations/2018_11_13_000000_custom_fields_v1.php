<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CustomFieldsV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields_field_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('field_id');
            $table->string('location_id');
            $table->string('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('custom_fields_field_type_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('field_id');
            $table->integer('type_id');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('custom_fields_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('field_id');
            $table->integer('type_id');
            $table->string('type');
            $table->integer('location_id');
            $table->morphs('model');
            $table->string('value')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('field_id');
        });

         Schema::create('custom_fields_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->string('icon');
            $table->string('class');
            $table->integer('type_id');
            $table->boolean('required')->default(0);
            $table->string('rule')->nullable();
            $table->boolean('enabled')->default(1);
            $table->string('locations');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::table('custom_fields_fields', function ($table) {
            $table->string('show')->default('always');
        });

        Schema::create('custom_fields_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('company_id')->nullable();
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });

        Schema::create('custom_fields_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('company_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('custom_fields_field_locations');
        Schema::drop('custom_fields_field_type_options');
        Schema::drop('custom_fields_field_values');
        Schema::drop('custom_fields_fields');
        Schema::drop('custom_fields_locations');
        Schema::drop('custom_fields_types');

    }
}
