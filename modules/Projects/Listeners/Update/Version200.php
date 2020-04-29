<?php

namespace Modules\Projects\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Version200 extends Listener
{
    use Permissions;

    const ALIAS = 'projects';

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
        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2019_06_19_000000_projects_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Projects\Database\Seeds\Dashboards',
        ]);

        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Projects\Database\Seeds\Reports',
        ]);
    }

    protected function updatePermissions()
    {
        $this->attachDefaultModulePermissions('projects', 'read-projects');
    }
}
