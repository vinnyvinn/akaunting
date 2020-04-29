<?php

namespace Modules\Crm\Listeners;

use App\Events\Module\Installed as Event;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Traits\Contacts;
use Illuminate\Support\Facades\Artisan;

class InstallModule
{
    use Contacts;

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'crm') {
            return;
        }

        $this->callSeeds();

        $this->updatePermissions();

        $this->addContactType();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Crm\Database\Seeds\Install',
        ]);
    }

    protected function updatePermissions()
    {
        // Check if already exists
        if ($p = Permission::where('name', 'read-crm-activities')->first()) {
            return;
        }

        $permissions = [];

        //Activity
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-activities',
            'display_name' => 'Read Crm Activities',
            'description' => 'Read Crm Activities',
        ]);

        //Companies
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-companies',
            'display_name' => 'Read Crm Companies',
            'description' => 'Read Crm Companies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-crm-companies',
            'display_name' => 'Read Crm Companies',
            'description' => 'Delete Crm Companies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-crm-companies',
            'display_name' => 'Update Crm Companies',
            'description' => 'Update Crm Companies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-crm-companies',
            'display_name' => 'Delete Crm Companies',
            'description' => 'Delete Crm Companies',
        ]);

        //Contacts
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-contacts',
            'display_name' => 'Read Crm Contacts',
            'description' => 'Read Crm Contacts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-crm-contacts',
            'display_name' => 'Read Crm Contacts',
            'description' => 'Delete Crm Contacts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-crm-contacts',
            'display_name' => 'Update Crm Contacts',
            'description' => 'Update Crm Contacts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-crm-contacts',
            'display_name' => 'Delete Crm Contacts',
            'description' => 'Delete Crm Contacts',
        ]);

        //Deals
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-deals',
            'display_name' => 'Read Crm Deals',
            'description' => 'Read Crm Deals',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-crm-deals',
            'display_name' => 'Read Crm Deals',
            'description' => 'Delete Crm Deals',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-crm-deals',
            'display_name' => 'Update Crm Deals',
            'description' => 'Update Crm Deals',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-crm-deals',
            'display_name' => 'Delete Crm Deals',
            'description' => 'Delete Crm Deals',
        ]);

        //Schedules
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-schedules',
            'display_name' => 'Read Crm Schedules',
            'description' => 'Read Crm Schedules',
        ]);

        //Settings
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-crm-settings',
            'display_name' => 'Read Crm Settings',
            'description' => 'Read Crm Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-crm-settings',
            'display_name' => 'Update CRM Settings',
            'description' => 'Update CRM Settings',
        ]);

        // Attach permission to roles
        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                if ($role->hasPermission($permission->name)) {
                    continue;
                }

                $role->attachPermission($permission);
            }
        }
    }

    protected function addContactType()
    {
        setting()->setExtraColumns(['company_id' => session('company_id')]);
        setting()->forgetAll();
        setting()->load(true);

        $this->addCustomerType('crm_contact');
        $this->addCustomerType('crm_company');

        setting()->forgetAll();
    }
}
