<?php

namespace Modules\Projects\Database\Seeds;

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
        if ($p = Permission::where('name', 'read-projects')->pluck('id')->first()) {
            return;
        }

        $permissions = [];
        
        // Projects
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-projects',
            'display_name' => 'Create Projects',
            'description' => 'Create Projects',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-projects',
            'display_name' => 'Read Projects',
            'description' => 'Read Projects',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-projects',
            'display_name' => 'Update Projects',
            'description' => 'Update Projects',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-projects',
            'display_name' => 'Delete Projects',
            'description' => 'Delete Projects',
        ]);
        
        // Tasks
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-project-tasks',
            'display_name' => 'Create Project Tasks',
            'description' => 'Create Project Tasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-project-tasks',
            'display_name' => 'Read Project Tasks',
            'description' => 'Read Project Tasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-project-tasks',
            'display_name' => 'Update Project Tasks',
            'description' => 'Update Project Tasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-project-tasks',
            'display_name' => 'Delete Project Tasks',
            'description' => 'Delete Project Tasks',
        ]);
        
        // Subtasks
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-project-subtasks',
            'display_name' => 'Create Project Subtasks',
            'description' => 'Create Project Subtasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-project-subtasks',
            'display_name' => 'Read Project Subtasks',
            'description' => 'Read Project Subtasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-project-subtasks',
            'display_name' => 'Update Project Subtasks',
            'description' => 'Update Project Subtasks',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-project-subtasks',
            'display_name' => 'Delete Project Subtasks',
            'description' => 'Delete Project Subtasks',
        ]);

        // Discussion
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-project-discussions',
            'display_name' => 'Create Project Discussions',
            'description' => 'Create Project Discussions',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-project-discussions',
            'display_name' => 'Read Project Discussions',
            'description' => 'Read Project Discussions',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-project-discussions',
            'display_name' => 'Update Project Discussions',
            'description' => 'Update Project Discussions',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-project-discussions',
            'display_name' => 'Delete Project Discussions',
            'description' => 'Delete Project Discussions',
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-project-comments',
            'display_name' => 'Create Project Comments',
            'description' => 'Create Project Comments',
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
