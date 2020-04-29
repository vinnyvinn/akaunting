<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Purchase\Bill;
use Modules\Projects\Models\ProjectBill;

class TotalBill extends Widget
{
    public $default_name = 'projects::general.widgets.total_bill';
    
    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-3'
        ];
    }

    public function show($project = null)
    {
        $billTotal = 0;
        $billTotalAmount = 0;
        
        if ($project) {
            $hasProject = true;
            $ids = $project->bills->pluck('bill_id');
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $hasProject = false;
            $ids = ProjectBill::where('company_id', session('company_id'))->pluck('bill_id');
            $this->views['header'] = 'projects::widgets.stats_header';
        }

        $billTotal = $this->applyFilters(Bill::accrued()->whereIn('id', $ids))
            ->get()
            ->count();

        $this->applyFilters(Bill::accrued()->whereIn('id', $ids))
            ->get()
            ->each(function ($item, $key) use (&$billTotalAmount) {
                $billTotalAmount += $item->getAmountConvertedToDefault();
            });

        return $this->view('projects::widgets.total_bill', [
            'billTotal' => $billTotal,
            'billTotalAmount' => $billTotalAmount,
            'hasProject' => $hasProject,
        ]);
    }
}
