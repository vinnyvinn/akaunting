<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DoubleEntryV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Account Bank
         Schema::create('double_entry_account_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('account_id');
            $table->integer('bank_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Accounts
        Schema::create('double_entry_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('type_id');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('system')->default(0);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type_id');
        });

        Schema::table('double_entry_accounts', function (Blueprint $table) {
            $table->dropColumn('system');
        });

        //Classes
        Schema::create('double_entry_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        //Journals
        Schema::create('double_entry_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->date('paid_at');
            $table->double('amount', 15, 4);
            $table->text('description');
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        //Ledger
        Schema::create('double_entry_ledger', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('account_id');
            $table->morphs('ledgerable');
            $table->date('issued_at');
            $table->string('entry_type');
            $table->double('debit', 15, 4)->nullable();
            $table->double('credit', 15, 4)->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('account_id');
        });

        Schema::table('double_entry_ledger', function (Blueprint $table) {
            $table->dateTime('issued_at')->change();
        });

        //Types
        Schema::create('double_entry_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('class_id');
        });

        //Account Tax
        Schema::create('double_entry_account_tax', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('account_id');
            $table->integer('tax_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('double_entry_account_bank');
        Schema::drop('double_entry_accounts');
        Schema::drop('double_entry_classes');
        Schema::drop('double_entry_ledger');
        Schema::drop('double_entry_types');
        Schema::drop('double_entry_account_tax');
        Schema::drop('double_entry_journals');
    }
}
