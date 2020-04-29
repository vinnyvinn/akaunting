<?php

namespace Modules\SalesMetrics\Database\Seeds;

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
                'name' => trans('sales-metrics::general.name'),
                'enabled' => 1,
            ]);
            
            $widgets = [
                'Modules\SalesMetrics\Widgets\TopCustomersRevenueBased',
                'Modules\SalesMetrics\Widgets\TopCustomersProfitBased',
                'Modules\SalesMetrics\Widgets\TopItemsRevenueBased',
                'Modules\SalesMetrics\Widgets\TopItemsProfitBased',
                'Modules\SalesMetrics\Widgets\TopItemsProfitMarginBased',
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
