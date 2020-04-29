<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use Modules\Crm\Models\Deal;

class LatestDeals extends Widget
{
    public $default_name = 'crm::widgets.latest_deals';

    public function show()
    {
        $deals = Deal::orderBy('created_at', 'desc')->take(10)->get();

        return $this->view('crm::widgets.latest_deals', [
            'deals' => $deals,
        ]);
    }
}
