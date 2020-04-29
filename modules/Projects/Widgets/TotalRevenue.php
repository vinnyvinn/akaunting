<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use Modules\Projects\Models\ProjectRevenue;

class TotalRevenue extends Widget
{

    public $default_name = 'projects::general.widgets.total_revenue';

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-3'
        ];
    }

    public function show($project = null)
    {
        $revenueTotal = 0;
        $revenueTotalAmount = 0;

        if ($project) {
            $hasProject = true;
            $ids = $project->revenues->pluck('revenue_id');
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $hasProject = false;
            $ids = ProjectRevenue::where('company_id', session('company_id'))->pluck('revenue_id');
            $this->views['header'] = 'projects::widgets.stats_header';
        }

        $revenueTotal = $this->applyFilters(Transaction::whereIn('id', $ids)->type('income')->isNotDocument())
            ->get()
            ->count();

        $this->applyFilters(Transaction::whereIn('id', $ids)->type('income')->isNotDocument())
            ->get()
            ->each(function ($item, $key) use (&$revenueTotalAmount) {
            $revenueTotalAmount += $item->getAmountConvertedToDefault();
        });

        return $this->view('projects::widgets.total_revenue', [
            'revenueTotal' => $revenueTotal,
            'revenueTotalAmount' => $revenueTotalAmount,
            'hasProject' => $hasProject,
        ]);
    }
}
