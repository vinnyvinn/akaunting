<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use Modules\Crm\Models\Deal;

class TotalDeals extends Widget
{
    public $default_name = 'crm::widgets.total_deals';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $deals = Deal::all()->count();

        return $this->view('crm::widgets.total_deals', [
            'deals' => $deals,
        ]);
    }
}
