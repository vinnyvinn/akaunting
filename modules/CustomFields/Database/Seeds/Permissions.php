<?php

namespace Modules\CustomFields\Database\Seeds;

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
        if ($p = Permission::where('name', 'read-custom-fields-fields')->value('id')) {
            return;
        }

        $permissions = [];

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-custom-fields-fields',
            'display_name' => 'Read Custom Fields',
            'description' => 'Read Custom Fields',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-custom-fields-fields',
            'display_name' => 'Create Custom Fields',
            'description' => 'Create Custom Fields',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-custom-fields-fields',
            'display_name' => 'Update Custom Fields',
            'description' => 'Update Custom Fields',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-custom-fields-fields',
            'display_name' => 'Delete Custom Fields',
            'description' => 'Delete Custom Fields',
        ]);

        //Setting
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-custom-fields-settings',
            'display_name' => 'Read Custom Fields Settings',
            'description' => 'Read Custom Fields Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-custom-fields-settings',
            'display_name' => 'Update Custom Fields Settings',
            'description' => 'Update Custom Fields Settings',
        ]);

        // Attach permission to roles
        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                $role->attachPermission($permission);
            }
        }
    }
}
