<?php

namespace Modules\Projects\Listeners;

use App\Events\Module\Installed as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectBill;
use Modules\Projects\Models\ProjectInvoice;
use Modules\Projects\Models\ProjectPayment;

class InstallModule
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'projects') {
            return;
        }

        $this->callSeeds();

        if (Schema::hasTable('projects_old')) {
            $this->copyOldData();

            $this->deleteOldTables();
            
            Artisan::call('module:delete', ['alias' => 'project', 'company_id' => session('company_id')]);
        }
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Projects\Database\Seeds\Install',
        ]);
    }

    protected function copyOldData() {
        $projects = DB::table('projects_old')->get();
        $projects_invoices = DB::table('projects_invoices_old')->get();
        $projects_bills = DB::table('projects_bills_old')->get();
        $projects_payments = DB::table('projects_payments_old')->get();

        foreach ($projects as $item) {
            Project::create([
                'id' => $item->id,
                'company_id' => $item->company_id,
                'name' => $item->name,
                'description' => $item->description,
                'customer_id' => $item->customer_id,
                'started_at' => $item->start_at,
                'ended_at' => $item->end_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
            ]);
        }

        foreach ($projects_invoices as $item) {
            ProjectInvoice::create([
                'id' => $item->id,
                'company_id' => $item->company_id,
                'project_id' => $item->project_id,
                'invoice_id' => $item->invoice_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
            ]);
        }

        foreach ($projects_bills as $item) {
            ProjectBill::create([
                'id' => $item->id,
                'company_id' => $item->company_id,
                'project_id' => $item->project_id,
                'bill_id' => $item->bill_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
            ]);
        }

        foreach ($projects_payments as $item) {
            ProjectPayment::create([
                'id' => $item->id,
                'company_id' => $item->company_id,
                'project_id' => $item->project_id,
                'payment_id' => $item->payment_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
            ]);
        }
    }

    protected function deleteOldTables()
    {
        Schema::dropIfExists('projects_old');
        Schema::dropIfExists('projects_invoices_old');
        Schema::dropIfExists('projects_bills_old');
        Schema::dropIfExists('projects_payments_old');
    }
}