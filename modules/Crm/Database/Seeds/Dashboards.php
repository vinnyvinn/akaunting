<?php

namespace Modules\Crm\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Company;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget;
use Illuminate\Database\Seeder;

class Dashboards extends Seeder
{
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
        $company_id = $this->command->argument('company');

        Company::find($company_id)->users()->each(function ($user) use ($company_id) {
            $dashboard = Dashboard::create([
                'company_id' => $company_id,
                'name' => trans('crm::general.name'),
                'enabled' => 1,
            ]);

            $widgets = [
                'Modules\Crm\Widgets\TotalDeals',
                'Modules\Crm\Widgets\TotalCompanies',
                'Modules\Crm\Widgets\TotalContacts',
                'Modules\Crm\Widgets\DealFlow',
                'Modules\Crm\Widgets\TodaySchedule',
                'Modules\Crm\Widgets\UpcomingSchedule',
                'Modules\Crm\Widgets\LatestDeals',
            ];

            $sort = 1;

            foreach ($widgets as $class_name) {
                $class = new $class_name();

                Widget::create([
                    'company_id' => $company_id,
                    'dashboard_id' => $dashboard->id,
                    'class' => $class_name,
                    'name' => $class->getDefaultName(),
                    'settings' => $class->getDefaultSettings(),
                    'sort' => $sort,
                ]);

                $sort++;
            }

            $user->dashboards()->attach($dashboard->id);
        });
    }
}
