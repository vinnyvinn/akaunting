<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrmV200 extends Migration
{
    /**
     * Run the migrations .
     *
     * @return void
     */
    public function up()
    {
        // Contact
        $rename_crm = [
            'user'              => 'created_by',
            'core_customer_id'  => 'contact_id',
            'birth_date'        => 'born_at',
            'contact_source'    => 'source',
            'owner'             => 'owner_id',
            'notes'             => 'note',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_contacts', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_contacts', function (Blueprint $table) {
            $table->integer('owner_id')->nullable()->change();
            $table->longtext('note')->nullable()->change();


            $table->index(['company_id', 'contact_id', 'created_by']);
        });

        // Company
        $rename_crm = [
            'user'              => 'created_by',
            'core_customer_id'  => 'contact_id',
            'contact_source'    => 'source',
            'owner'             => 'owner_id',
            'notes'             => 'note',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_companies', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_companies', function (Blueprint $table) {
            $table->integer('owner_id')->nullable()->change();
            $table->longtext('note')->nullable()->change();

            $table->index(['company_id', 'contact_id', 'created_by']);
        });

        // CompanyContact
        Schema::table('crm_company_contacts', function (Blueprint $table) {
            $table->renameColumn('contact_id', 'crm_contact_id');
        });

        Schema::table('crm_company_contacts', function (Blueprint $table) {
            $table->integer('created_by')->after('crm_contact_id')->default();

            $table->index(['company_id', 'created_by']);
        });

        // Note
        $rename_crm = [
            'registered_user'   => 'created_by',
            'note'             => 'message',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_notes', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_notes', function (Blueprint $table) {
            $table->longText('message')->change();

            $table->index(['company_id', 'created_by']);
        });

        // Email
        Schema::table('crm_emails', function (Blueprint $table) {
            $table->renameColumn('registered_user', 'created_by');
            $table->text('subject')->change();

            $table->index(['company_id', 'created_by', 'from', 'to']);
        });

        // Log
        $rename_crm = [
            'registered_user'   => 'created_by',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_logs', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_logs', function (Blueprint $table) {
            $table->dropColumn('users');
            //$table->time('time')->change();
            $table->text('subject')->nullable()->change();
            $table->longText('description')->nullable()->change();

            $table->index(['company_id', 'created_by', 'date', 'type']);
        });

        // Schedule
        $rename_crm = [
            'registered_user'   => 'created_by',
            'title'             => 'name',
            'start_date'        => 'started_at',
            'start_time'        => 'started_time_at',
            'end_date'          => 'ended_at',
            'end_time'          => 'ended_time_at',
            'user'              => 'user_id',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_schedules', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->date('started_at')->change();
            $table->date('started_time_at')->change();
            //$table->time('start_time')->change();
            //$table->time('end_time')->change();
            //$table->integer('user')->nullable()->change();

            $table->index(['company_id', 'created_by', 'type', 'name']);
        });

        // Task
        $rename_crm = [
            'registered_user'   => 'created_by',
            'title'             => 'name',
            'start_date'        => 'started_at',
            'start_time'        => 'started_time_at',
            'user'              => 'user_id',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_tasks', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_tasks', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->date('started_at')->change();
            //$table->time('start_time')->change();
            //$table->integer('user')->nullable()->change();

            $table->index(['company_id', 'created_by', 'started_at', 'name']);
        });

        // Deal
        $rename_crm = [
            'contact_id'    => 'crm_contact_id',
            'title'         => 'name',
            'owner'         => 'owner_id',
            'deal_value'    => 'amount',
            'close_date'    => 'closed_at',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_deals', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('crm_deals', function (Blueprint $table) {
            $table->dateTime('closed_at')->change();
            $table->integer('created_by')->after('status')->default();

            $table->index(['company_id', 'created_by', 'name', 'amount']);
        });

        // Deal Activity
        $rename_crm = [
            'registered_user'   => 'created_by',
            'title'             => 'name',
        ];

        foreach ($rename_crm as $from => $to) {
            Schema::table('crm_deal_activities', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        // Deal Competitor
        Schema::table('crm_deal_competitors', function (Blueprint $table) {
            $table->renameColumn('registered_user', 'created_by');
        });

        // Deal Activity Type
        Schema::rename('crm_setting_deal_activity_types', 'crm_deal_activity_types');

        Schema::table('crm_deal_activity_types', function (Blueprint $table) {
            $table->renameColumn('title', 'name');

        });

        Schema::table('crm_deal_activity_types', function (Blueprint $table) {
            $table->integer('created_by')->after('rank')->default();

            $table->index(['company_id', 'created_by']);
        });

        // Deal Agent
        Schema::table('crm_deal_agents', function (Blueprint $table) {
            $table->integer('created_by')->after('user_id')->default();

            $table->index(['company_id', 'created_by', 'deal_id', 'user_id']);
        });

        // Deal Pipeline
        Schema::rename('crm_setting_deal_pipelines', 'crm_deal_pipelines');
        Schema::table('crm_deal_pipelines',function (Blueprint $table) {
            $table->renameColumn('title', 'name');

        });

        Schema::table('crm_deal_pipelines',function (Blueprint $table) {
            $table->integer('created_by')->after('name')->default();

            $table->index(['company_id', 'created_by', 'name']);
        });

        // crm_deal_pipeline_stages
        Schema::rename('crm_setting_deal_pipeline_stages', 'crm_deal_pipeline_stages');
        Schema::table('crm_deal_pipeline_stages', function (Blueprint $table) {
            $table->integer('created_by')->after('rank')->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('crm_contacts');
        Schema::drop('crm_notes');
        Schema::drop('crm_deal_notes');
        Schema::drop('crm_deal_emails');
        Schema::drop('crm_emails');
        Schema::drop('crm_logs');
        Schema::drop('crm_schedules');
        Schema::drop('crm_tasks');
        Schema::drop('crm_companies');
        Schema::drop('crm_company_contacts');
        Schema::drop('crm_deals');
        Schema::drop('crm_deal_activities');
        Schema::drop('crm_deal_competitors');
        Schema::drop('crm_deal_activity_types');
        Schema::drop('crm_deal_agents');
        Schema::drop('crm_deal_pipelines');
        Schema::drop('crm_deal_pipeline_stages');
    }
}
