<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Sale\Invoice;
use Modules\Projects\Models\ProjectInvoice;

class TotalInvoice extends Widget
{
    public $default_name = 'projects::general.widgets.total_invoice';

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-3'
        ];
    }

    public function show($project = null)
    {
        $invoiceTotal = 0;
        $invoiceTotalAmount = 0;
        
        if ($project) {
            $hasProject = true;
            $ids = $project->invoices->pluck('invoice_id');
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $hasProject = false;
            $ids = ProjectInvoice::where('company_id', session('company_id'))->pluck('invoice_id');
            $this->views['header'] = 'projects::widgets.stats_header';
        }

        $invoiceTotal = $this->applyFilters(Invoice::accrued()->whereIn('id', $ids))
            ->get()
            ->count();

        $this->applyFilters(Invoice::accrued()->whereIn('id', $ids))
            ->get()
            ->each(function ($item, $key) use (&$invoiceTotalAmount) {
                $invoiceTotalAmount += $item->getAmountConvertedToDefault();
            });

        return $this->view('projects::widgets.total_invoice', [
            'invoiceTotal' => $invoiceTotal,
            'invoiceTotalAmount' => $invoiceTotalAmount,
            'hasProject' => $hasProject,
        ]);
    }
}
