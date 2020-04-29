<?php

namespace Modules\Crm\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Traits\Contacts;
use App\Traits\Permissions;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version200 extends Listener
{
    use Contacts, Permissions;

    const ALIAS = 'crm';

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

        $this->addContactType();
    }

    protected function updateDatabase()
    {
        if (DB::table('migrations')->where('migration', '2018_10_02_000000_crm_v1')->first()) {
            return;
        }

        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2018_10_02_000000_crm_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);

        $classes = [
            'Modules\Crm\Models\Contact\Contact' => 'Modules\Crm\Models\Contact',
            'Modules\Crm\Models\Company\Company' => 'Modules\Crm\Models\Company',
        ];

        foreach ($classes as $old_class => $new_class) {
            DB::table('crm_notes')
            ->where('noteable_type', $old_class)
            ->update([
                'noteable_type' => $new_class,
            ]);

            DB::table('crm_emails')
            ->where('emailable_type', $old_class)
            ->update([
                'emailable_type' => $new_class,
            ]);

            DB::table('crm_logs')
            ->where('logable_type', $old_class)
            ->update([
                'logable_type' => $new_class,
            ]);

            DB::table('crm_schedules')
            ->where('scheduleable_type', $old_class)
            ->update([
                'scheduleable_type' => $new_class,
            ]);

            DB::table('crm_tasks')
            ->where('taskable_type', $old_class)
            ->update([
                'taskable_type' => $new_class,
            ]);
        }

        $this->copyData();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Crm\Database\Seeds\Dashboards',
        ]);

        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Crm\Database\Seeds\Reports',
        ]);
    }

    public function copyData()
    {
        //contact
        $crm_contacts = DB::table('crm_contacts')->cursor();

        foreach ($crm_contacts as $crm_contact) {
            if (empty($crm_contact->contact_id)) {
                $contact_id = DB::table('contacts')->insertGetId([
                    'company_id' => $crm_contact->company_id,
                    'type' => 'crm_contact',
                    'name' => $crm_contact->name,
                    'email' => $crm_contact->email,
                    'phone' => $crm_contact->phone,
                    'address' => $crm_contact->address,
                    'website' => $crm_contact->web_site,
                    'currency_code' => setting('default.currency'),
                    'enabled' => 1,
                    'created_at' => $crm_contact->created_at,
                    'updated_at' => $crm_contact->updated_at,
                    'deleted_at' => $crm_contact->deleted_at,
                ]);

                DB::table('crm_contacts')
                    ->where('id', $crm_contact->id)
                    ->update([
                        'contact_id' => $contact_id,
                    ]);
            } else {
                DB::table('contacts')
                    ->where('id', $crm_contact->contact_id)
                    ->where('type', 'customer')
                    ->update([
                        'type' => 'crm_contact',
                    ]);
            }
        }

        Schema::table('crm_contacts', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('web_site');
            $table->dropColumn('address');
        });

        //Company
        $crm_companies = DB::table('crm_companies')->cursor();

        foreach ($crm_companies as $crm_company) {
            if (empty($crm_company->contact_id)) {
                $contact_id = DB::table('contacts')->insertGetId([
                    'company_id' => $crm_company->company_id,
                    'type' => 'crm_company',
                    'name' => $crm_company->name,
                    'email' => $crm_company->email,
                    'phone' => $crm_company->phone,
                    'address' => $crm_company->address,
                    'website' => $crm_company->web_site,
                    'currency_code' => setting('default.currency'),
                    'enabled' => 1,
                    'created_at' => $crm_company->created_at,
                    'updated_at' => $crm_company->updated_at,
                    'deleted_at' => $crm_company->deleted_at,
                ]);

                DB::table('crm_companies')
                    ->where('id', $crm_company->id)
                    ->update([
                        'contact_id' => $contact_id,
                    ]);
            } else {
                DB::table('contacts')
                    ->where('id', $crm_company->contact_id)
                    ->where('type', 'customer')
                    ->update([
                        'type' => 'crm_company',
                    ]);
            }
        }

        Schema::table('crm_companies', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('web_site');
            $table->dropColumn('address');
        });

        //Note
        $crm_notes = DB::table('crm_deal_notes')->cursor();

        foreach ($crm_notes as $crm_note) {
            $data = [
                'company_id' => $crm_note->company_id,
                'created_by' => $crm_note->registered_user,
                'noteable_type' => 'Modules\\Crm\\Models\\Deal',
                'noteable_id' => $crm_note->deal_id,
                'message' => $crm_note->note,
            ];

            unset($data['id']);

            DB::table('crm_notes')->insert($data);
        }

        Schema::drop('crm_deal_notes');

        //Email
        $crm_emails = DB::table('crm_deal_emails')->cursor();

        foreach ($crm_emails as $crm_email) {
            $data = [
                'company_id' => $crm_email->company_id,
                'created_by' => $crm_email->registered_user,
                'emailable_type' => 'Modules\\Crm\\Models\\Deal',
                'emailable_id' => $crm_email->deal_id,
                'from' => $crm_email->to,
                'to' => $crm_email->to,
                'subject' => $crm_email->subject,
                'body' => $crm_email->body,
            ];

            unset($data['id']);

            DB::table('crm_emails')->insert($data);
        }
        Schema::drop('crm_deal_emails');
    }

    public function updatePermissions()
    {
        if ($p = Permission::where('name', 'read-crm-reports-activity')->first()) {
            return;
        }

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'update-crm-settings',
            'display_name' => 'Update CRM Settings',
            'description' => 'Update CRM Settings',
        ]);

        $detach_permissions = [
            'read-reports-activity',
            'read-reports-growth',
            'read-crm-dashboard',

        ];

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($attach_permissions as $permission) {
                $this->attachPermission($role, $permission);
            }

            foreach ($detach_permissions as $permission_name) {
                $this->detachPermission($role, $permission_name);
            }
        }

        $this->attachDefaultModulePermissions('crm');
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
