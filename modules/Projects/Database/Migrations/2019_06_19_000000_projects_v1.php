<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectsV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('projects_invoices')) {
            Schema::rename('projects', 'projects_old');
            Schema::rename('projects_invoices', 'projects_invoices_old');
            Schema::rename('projects_bills', 'projects_bills_old');
            Schema::rename('projects_payments', 'projects_payments_old');
        }

        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('customer_id');
            $table->integer('status')->default(0);
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('invoice_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_revenues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('revenue_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('bill_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('payment_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_sub_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('task_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('status')->nullable();
            $table->date('deadline_at')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('created_by');
            $table->integer('order_number')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('discussion_id');
            $table->text('comment');
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('total_comment')->default(0);
            $table->integer('total_like')->default(0);
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('activity_id');
            $table->string('activity_type');
            $table->text('description');
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_discussion_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('discussion_id');
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_subtask_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('task_id');
            $table->integer('subtask_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_invoices');
        Schema::dropIfExists('project_revenues');
        Schema::dropIfExists('project_bills');
        Schema::dropIfExists('project_payments');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_sub_tasks');
        Schema::dropIfExists('project_comments');
        Schema::dropIfExists('project_discussions');
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('project_discussion_likes');
        Schema::dropIfExists('project_users');
        Schema::dropIfExists('project_subtask_users');
    }
}
