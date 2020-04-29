<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use Modules\Projects\Models\ProjectPayment;

class TotalPayment extends Widget
{

    public $default_name = 'projects::general.widgets.total_payment';

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-3'
        ];
    }

    public function show($project = null)
    {
        $paymentTotal = 0;
        $paymentTotalAmount = 0;

        if ($project) {
            $hasProject = true;
            $ids = $project->payments->pluck('payment_id');
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $hasProject = false;
            $ids = ProjectPayment::where('company_id', session('company_id'))->pluck('payment_id');
            $this->views['header'] = 'projects::widgets.stats_header';
        }

        $paymentTotal = $this->applyFilters(Transaction::whereIn('id', $ids)->type('expense')->isNotDocument())
            ->get()
            ->count();

        $this->applyFilters(Transaction::whereIn('id', $ids)->type('expense')->isNotDocument())
            ->get()
            ->each(function ($item, $key) use (&$paymentTotalAmount) {
            $paymentTotalAmount += $item->getAmountConvertedToDefault();
        });

        return $this->view('projects::widgets.total_payment', [
            'paymentTotal' => $paymentTotal,
            'paymentTotalAmount' => $paymentTotalAmount,
            'hasProject' => $hasProject,
        ]);
    }
}
