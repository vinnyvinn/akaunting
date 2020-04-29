<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectRevenue;

class LatestIncome extends Widget
{
    public $default_name = 'projects::general.widgets.latest_income_by_project';

    public function show($project = null)
    {
        if ($project) {
            $this->model->name = trans('projects::general.widgets.latest_income');
            $ids = $project->revenues()->pluck('revenue_id');
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $ids = ProjectRevenue::where('company_id', session('company_id'))->pluck('revenue_id');
        }

        $transactions = $this->applyFilters(Transaction::with('category')->type('income')
            ->whereIn('id', $ids)
            ->orderBy('paid_at', 'desc')
            ->isNotTransfer()
            ->take(5))
            ->get();

        return $this->view('projects::widgets.latest_income', [
            'transactions' => $transactions,
            'project' => $project
        ]);
    }
}
