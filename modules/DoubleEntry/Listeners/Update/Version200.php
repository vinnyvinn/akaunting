<?php

namespace Modules\DoubleEntry\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Common\Company;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Version200 extends Listener
{
    use Permissions;

    const ALIAS = 'double-entry';

    const VERSION = '2.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->callSeeds();

        $this->updatePermissions();
    }

    protected function updateDatabase()
    {
        if (DB::table('migrations')->where('migration', '2018_05_09_000000_double_entry_v1')->first()) {
            return;
        }

        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2018_05_09_000000_double_entry_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Income\Invoice')
            ->update([
                'ledgerable_type' => 'App\Models\Sale\Invoice',
            ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Income\InvoiceItem')
            ->update([
                'ledgerable_type' => 'App\Models\Sale\InvoiceItem',
            ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Income\InvoiceItemTax')
            ->update([
                'ledgerable_type' => 'App\Models\Sale\InvoiceItemTax',
            ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Expense\Bill')
            ->update([
                'ledgerable_type' => 'App\Models\Purchase\Bill',
            ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Expense\BillItem')
            ->update([
                'ledgerable_type' => 'App\Models\Purchase\BillItem',
            ]);

        DB::table('double_entry_ledger')
            ->where('ledgerable_type', 'App\Models\Expense\BillItemTax')
            ->update([
                'ledgerable_type' => 'App\Models\Purchase\BillItemTax',
            ]);
    }

    protected function callSeeds()
    {
        if (!$company = Company::find(session('company_id'))) {
            return;
        }

        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\DoubleEntry\Database\Seeds\Dashboards',
        ]);

        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\DoubleEntry\Database\Seeds\Reports',
        ]);
    }

    protected function updatePermissions()
    {
        if ($p = Permission::where('name', 'read-double-entry-settings')->pluck('id')->first()) {
            return;
        }

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'read-double-entry-settings',
            'display_name' => 'Read Double-Entry Settings',
            'description' => 'Read Double-Entry Settings',
        ]);

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'update-double-entry-settings',
            'display_name' => 'Update Double-Entry Settings',
            'description' => 'Update Double-Entry Settings',
        ]);

        $detach_permissions = [
            'read-double-entry-trial-balance',
            'read-double-entry-balance-sheet',
            'read-double-entry-general-ledger',
        ];

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-double-entry-chart-of-accounts');
        });

        foreach ($roles as $role) {
            foreach ($attach_permissions as $permission) {
                $this->attachPermission($role, $permission);
            }

            foreach ($detach_permissions as $permission_name) {
                $this->detachPermission($role, $permission_name);
            }
        }

        $this->attachDefaultModulePermissions('double-entry', 'read-double-entry-chart-of-accounts');
    }
}
