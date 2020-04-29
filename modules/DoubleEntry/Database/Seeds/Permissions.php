<?php

namespace Modules\DoubleEntry\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        // Check if already exists
        if ($p = Permission::where('name', 'read-double-entry-chart-of-accounts')->pluck('id')->first()) {
            return;
        }

        $permissions = [];

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-double-entry-chart-of-accounts',
            'display_name' => 'Create Double-Entry Chart of Accounts',
            'description' => 'Create Double-Entry Chart of Accounts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-double-entry-chart-of-accounts',
            'display_name' => 'Read Double-Entry Chart of Accounts',
            'description' => 'Read Double-Entry Chart of Accounts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-double-entry-chart-of-accounts',
            'display_name' => 'Update Double-Entry Chart of Accounts',
            'description' => 'Update Double-Entry Chart of Accounts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-double-entry-chart-of-accounts',
            'display_name' => 'Delete Double-Entry Chart of Accounts',
            'description' => 'Delete Double-Entry Chart of Accounts',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-double-entry-journal-entry',
            'display_name' => 'Create Double-Entry Journal Entry',
            'description' => 'Create Double-Entry Journal Entry',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-double-entry-journal-entry',
            'display_name' => 'Read Double-Entry Journal Entry',
            'description' => 'Read Double-Entry Journal Entry',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-double-entry-journal-entry',
            'display_name' => 'Update Double-Entry Journal Entry',
            'description' => 'Update Double-Entry Journal Entry',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-double-entry-journal-entry',
            'display_name' => 'Delete Double-Entry Journal Entry',
            'description' => 'Delete Double-Entry Journal Entry',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-double-entry-settings',
            'display_name' => 'Read Double-Entry Settings',
            'description' => 'Read Double-Entry Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-double-entry-settings',
            'display_name' => 'Update Double-Entry Settings',
            'description' => 'Update Double-Entry Settings',
        ]);

        // Attach permission to roles
        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                $this->attachPermission($role, $permission);
            }
        }
    }
}
