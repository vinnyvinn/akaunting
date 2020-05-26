<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class InventoryV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Inventory histories
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->morphs('type');
            $table->double('quantity', 7, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Item group options
         Schema::create('inventory_item_group_option_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('option_id');
            $table->integer('option_value_id');
            $table->integer('item_group_id');
            $table->integer('item_group_option_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Item group options values
        Schema::create('inventory_item_group_option_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_group_id');
            $table->integer('item_group_option_id');
            $table->integer('option_id');
            $table->integer('option_value_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Item group options options
        Schema::create('inventory_item_group_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_group_id');
            $table->integer('option_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Item groups
         Schema::create('inventory_item_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('tax_id')->nullable();
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Inventory items
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->double('opening_stock', 7, 2)->nullable();
            $table->double('opening_stock_value', 15, 4)->nullable();
            $table->double('reorder_level')->nullable();
            $table->integer('vendor_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Manufacturer items
         Schema::create('inventory_manufacturer_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('manufacturer_id');
            $table->integer('item_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Manufacturer vendors
        Schema::create('inventory_manufacturer_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('manufacturer_id');
            $table->integer('vendor_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Manufacturers
         Schema::create('inventory_manufacturers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Option Values
        Schema::create('inventory_option_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('option_id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Options
         Schema::create('inventory_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('type');
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Werehouse items
        Schema::create('inventory_warehouse_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('warehouse_id');
            $table->integer('item_id');
            $table->integer('quantity')->nullable()->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Werehouses
        Schema::create('inventory_warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'email', 'deleted_at']);
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventory_histories');
        Schema::drop('inventory_item_group_option_items');
        Schema::drop('inventory_item_group_option_values');
        Schema::drop('inventory_item_group_options');
        Schema::drop('inventory_item_groups');
        Schema::drop('inventory_items');
        Schema::drop('inventory_manufacturer_items');
        Schema::drop('inventory_manufacturer_vendors');
        Schema::drop('inventory_manufacturers');
        Schema::drop('inventory_option_values');
        Schema::drop('inventory_options');
        Schema::drop('inventory_warehouse_items');
        Schema::drop('inventory_warehouses');

    }
}
