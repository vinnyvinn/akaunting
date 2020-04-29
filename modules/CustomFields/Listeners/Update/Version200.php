<?php

namespace Modules\CustomFields\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\Location;

class Version200 extends Listener
{
    use Permissions;

    const ALIAS = 'custom-fields';

    const VERSION = '2.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
         if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDabase();

        $this->updateLocations();

        $this->updatePermissions();
    }

    public function updateDabase()
    {
        if (DB::table('migrations')->where('migration', '2018_11_13_000000_custom_fields_v1')->first()) {
            return;
        }

        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2018_11_13_000000_custom_fields_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);
        
        DB::table('custom_fields_field_values')
            ->where('model_type', 'App\Models\Income\Invoice')
            ->update([
                'model_type' => 'App\Models\Sale\Invoice',
            ]);

        DB::table('custom_fields_field_values')
            ->where('model_type', 'App\Models\Income\InvoiceItem')
            ->update([
                'model_type' => 'App\Models\Sale\InvoiceItem',
            ]);
        
        DB::table('custom_fields_field_values')
            ->where('model_type', 'App\Models\Expense\Bill')
            ->update([
                'model_type' => 'App\Models\Purchase\Bill',
            ]);

        DB::table('custom_fields_field_values')
            ->where('model_type', 'App\Models\Expense\BillItem')
            ->update([
                'model_type' => 'App\Models\Purchase\BillItem',
            ]);

        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updateLocations()
    {
        $locations = Location::all();

        foreach($locations as $location) {
            switch ($location->code) {
                case 'incomes.invoices':
                    $location->code = 'sales.invoices';
                    break;
                case'incomes.revenues':
                    $location->code = 'sales.revenues';
                    break;
                case'incomes.customers':
                    $location->code = 'sales.customers';
                    break;
                case'expenses.bills':
                    $location->code = 'purchases.bills';
                    break;
                case'expenses.payments':
                    $location->code = 'purchases.payments';
                    break;
                case'expenses.vendors':
                    $location->code = 'purchases.vendors';
                    break;
            }

            $location->save();
        }
    }

    public function updatePermissions()
    {
        if ($p = Permission::where('name', 'read-custom-fields-settings')->pluck('id')->first()) {
            return;
        }

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'read-custom-fields-settings',
            'display_name' => 'Read Custom Fields Settings',
            'description' => 'Read Custom Fields Settings',
        ]);

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'update-custom-fields-settings',
            'display_name' => 'Update Custom Fields Settings',
            'description' => 'Update Custom Fields Settings',
        ]);

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-custom-fields-fields');
        });

        foreach ($roles as $role) {
            foreach ($attach_permissions as $permission) {
                $this->attachPermission($role, $permission);
            }
        }
    }
}
