<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealPipelineStage;
use Date;

class DealFlow extends Widget
{
    use DateTime;

    public $today;

    public $financial_start;

    public $default_name = 'crm::widgets.deal_flow';

    public $default_settings = [
        'width' => 'col-md-12',
    ];

    public function show()
    {
        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $date = request();
        $start = Date::parse(request('start_date', $year_start));
        $end = Date::parse(request('end_date', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));

        $deals = Deal::all()->whereBetween('created_at', [$start, $end]);
        //$pipelines_select = Pipeline::where('company_id', session('company_id'))->pluck('title', 'id');
        //$owner = Company::find(session('company_id'))->users()->pluck('name', 'id');
        //$deals = Deal::where('owner', $filter_owner)->where('pipeline_id', $pipeline_filter)->get();

        $flow = $this->getFlow($deals);

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels(array_values($flow['labels']));

        $chart->dataset(trans_choice('crm::general.deals', 1), 'line', array_values($flow['totals']))
            ->backgroundColor('#6da252')
            ->color('#6da252')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
                ])
            ->fill(false);

        return $this->view('widgets.line_chart', [
            'chart' => $chart,
        ]);
    }

    private function getFlow($deals)
    {
        $labels = $totals = [];

        foreach ($deals as $deal) {
            $pipeline_id = $deal->pipeline()->pluck('id')->first();

            $stages = DealPipelineStage::where('pipeline_id', $pipeline_id)->get();

            foreach ($stages as $key => $stage) {
                $labels[$key] = $stage->name;

                if (!isset($totals[$key])) {
                    $totals[$key] = 0;
                }

                if ($deal->stage_id == $stage->id) {
                    $totals[$key] += $deal->getConvertedAmount();
                }
            }
        }

        return [
            'labels' => $labels,
            'totals' => $totals,
        ];
    }
}
