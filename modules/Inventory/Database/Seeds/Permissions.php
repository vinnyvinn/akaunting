<?php

namespace Modules\Inventory\Database\Seeds;

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
        if ($p = Permission::where('name', 'read-inventory-item-groups')->value('id')) {
            return;
        }

        $permissions = [];

        // Item Groups
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-item-groups',
            'display_name' => 'Create Inventory Item Groups',
            'description' => 'Create Inventory Item Groups',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-item-groups',
            'display_name' => 'Read Inventory Item Groups',
            'description' => 'Read Inventory Item Groups',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-item-groups',
            'display_name' => 'Update Inventory Item Groups',
            'description' => 'Update Inventory Item Groups',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-item-groups',
            'display_name' => 'Delete Inventory Item Groups',
            'description' => 'Delete Inventory Item Groups',
        ]);

        // Items
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-items',
            'display_name' => 'Create Inventory Items',
            'description' => 'Create Inventory Items',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-items',
            'display_name' => 'Read Inventory Items',
            'description' => 'Read Inventory Items',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-items',
            'display_name' => 'Update Inventory Items',
            'description' => 'Update Inventory Items',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-items',
            'display_name' => 'Delete Inventory Items',
            'description' => 'Delete Inventory Items',
        ]);

        // Options
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-options',
            'display_name' => 'Create Inventory Options',
            'description' => 'Create Inventory Options',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-options',
            'display_name' => 'Read Inventory Options',
            'description' => 'Read Inventory Options',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-options',
            'display_name' => 'Update Inventory Options',
            'description' => 'Update Inventory Options',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-options',
            'display_name' => 'Delete Inventory Options',
            'description' => 'Delete Inventory Options',
        ]);

        // Manufacturers
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-manufacturers',
            'display_name' => 'Create Inventory Manufacturers',
            'description' => 'Create Inventory Manufacturers',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-manufacturers',
            'display_name' => 'Read Inventory Manufacturers',
            'description' => 'Read Inventory Manufacturers',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-manufacturers',
            'display_name' => 'Update Inventory Manufacturers',
            'description' => 'Update Inventory Manufacturers',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-manufacturers',
            'display_name' => 'Delete Inventory Manufacturers',
            'description' => 'Delete Inventory Manufacturers',
        ]);

        // Warehouses
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-warehouses',
            'display_name' => 'Create Inventory Warehouses',
            'description' => 'Create Inventory Warehouses',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-warehouses',
            'display_name' => 'Read Inventory Warehouses',
            'description' => 'Read Inventory Warehouses',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-warehouses',
            'display_name' => 'Update Inventory Warehouses',
            'description' => 'Update Inventory Warehouses',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-warehouses',
            'display_name' => 'Delete Inventory Warehouses',
            'description' => 'Delete Inventory Warehouses',
        ]);

        // Histories
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-histories',
            'display_name' => 'Create Inventory Histories',
            'description' => 'Create Inventory Histories',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-histories',
            'display_name' => 'Read Inventory Histories',
            'description' => 'Read Inventory Histories',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-histories',
            'display_name' => 'Update Inventory Histories',
            'description' => 'Update Inventory Histories',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-histories',
            'display_name' => 'Delete Inventory Histories',
            'description' => 'Delete Inventory Histories',
        ]);

        // Reports
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-inventory-reports',
            'display_name' => 'Create Inventory Reports',
            'description' => 'Create Inventory Reports',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-reports',
            'display_name' => 'Read Inventory Reports',
            'description' => 'Read Inventory Reports',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-reports',
            'display_name' => 'Update Inventory Reports',
            'description' => 'Update Inventory Reports',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-inventory-reports',
            'display_name' => 'Delete Inventory Reports',
            'description' => 'Delete Inventory Reports',
        ]);

        // Settings
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-inventory-settings',
            'display_name' => 'Read Inventory Settings',
            'description' => 'Read Inventory Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-inventory-settings',
            'display_name' => 'Update Inventory Settings',
            'description' => 'Update Inventory Settings',
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
