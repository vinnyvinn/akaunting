<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrmV201 extends Migration
{
    /**
     * Run the migrations .
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_deals', function (Blueprint $table) {
            $table->string('color')->after('created_by')->default('#6da252');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_deals', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
