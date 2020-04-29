<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrmV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('user');
            $table->string('stage');
            $table->string('owner')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('contact_source');
            $table->string('notes')->nullable();
            $table->integer('core_customer_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('registered_user');
            $table->morphs('noteable');
            $table->string('note');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('registered_user');
            $table->morphs('emailable');
            $table->string('from');
            $table->string('to');
            $table->string('subject');
            $table->string('body')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->morphs('logable');
            $table->string('type');
            $table->date('date');
            $table->string('time');
            $table->string('description')->nullable();
            $table->integer('users')->nullable();
            $table->string('subject')->nullable();
            $table->integer('registered_user');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('registered_user');
            $table->morphs('scheduleable');
            $table->string('title')->nullable();
            $table->dateTime('start_date');
            $table->string('start_time');
            $table->dateTime('end_date');
            $table->string('end_time');
            $table->string('description')->nullable();
            $table->string('type');
            $table->string('user')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->morphs('taskable');
            $table->string('title')->nullable();
            $table->string('user')->nullable();
            $table->date('start_date');
            $table->string('start_time');
            $table->string('description')->nullable();
            $table->integer('registered_user');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('user');
            $table->string('stage');
            $table->string('owner')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('contact_source');
            $table->string('notes')->nullable();
            $table->integer('core_customer_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_company_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('crm_company_id');
            $table->integer('contact_id');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('contact_id')->nullable();
            $table->integer('crm_company_id')->nullable();
            $table->integer('pipeline_id');
            $table->integer('stage_id')->default(0);
            $table->string('title');
            $table->double('deal_value')->nullable();
            $table->integer('owner');
            $table->date('close_date')->nullable();
            $table->string('status')->default('open');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deal_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('deal_id');
            $table->integer('registered_user');
            $table->string('activity_type');
            $table->string('title')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('duration')->nullable();
            $table->integer('assigned');
            $table->string('note')->nullable();
            $table->boolean('done')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deal_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('deal_id');
            $table->integer('registered_user');
            $table->string('note');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deal_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('deal_id');
            $table->integer('registered_user');
            $table->string('to');
            $table->string('subject');
            $table->string('body')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deal_competitors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('deal_id');
            $table->integer('registered_user');
            $table->string('name')->nullable();
            $table->string('web_site')->nullable();
            $table->string('strengths')->nullable();
            $table->string('weaknesses')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_setting_deal_activity_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->integer('rank')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_deal_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('deal_id');
            $table->integer('user_id'); // user = agent

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_setting_deal_pipelines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('title');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_setting_deal_pipeline_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('pipeline_id');
            $table->string('name');
            $table->string('life_stage');
            $table->integer('rank')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
