<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use Modules\Crm\Models\Company;

class TotalCompanies extends Widget
{
    public $default_name = 'crm::widgets.total_companies';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $companies = Company::all()->count();

        return $this->view('crm::widgets.total_companies', [
            'companies' => $companies,
        ]);
    }
}
