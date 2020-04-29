<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Date;
use Modules\Projects\Models\ProjectBill;
use Modules\Projects\Models\ProjectPayment;
use Modules\Projects\Models\ProjectRevenue;
use Modules\Projects\Models\ProjectInvoice;

class ProjectLineChart extends Widget
{
    use Currencies, DateTime;

    public $default_name = 'projects::general.widgets.line_chart_by_project';

    public $default_settings = [
        'width' => 'col-md-12'
    ];

    public function show($project = null)
    {
        if ($project) {
            $this->model->name = trans('projects::general.widgets.line_chart');
            $this->views['header'] = 'projects::widgets.standard_header';
        }

        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $start = Date::parse(request('start_date', $year_start));
        $end = Date::parse(request('end_date', Date::parse($year_start)->addYear(1)
            ->subDays(1)
            ->format('Y-m-d')));
        $period = request('period', 'month');
        $range = request('range', 'custom');

        $start_month = $start->month;
        $end_month = $end->month;

        // Monthly
        $labels = [];

        $s = clone $start;

        if ($range == 'last_12_months') {
            $end_month = 12;
            $start_month = 0;
        } elseif ($range == 'custom') {
            $end_month = $end->diffInMonths($start);
            $start_month = 0;
        }

        for ($j = $end_month; $j >= $start_month; $j --) {
            $labels[$end_month - $j] = $s->format('M Y');

            if ($period == 'month') {
                $s->addMonth();
            } else {
                $s->addMonths(3);
                $j -= 2;
            }
        }

        $income = $this->calculateTotals('income', $start, $end, $period, $project);
        $expense = $this->calculateTotals('expense', $start, $end, $period, $project);
        $profit = $this->calculateProfit($income, $expense);

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels(array_values($labels));

        $chart->dataset(trans_choice('general.incomes', 1), 'line', array_values($income))
            ->backgroundColor('#328aef')
            ->color('#328aef')
            ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line'
        ])
            ->fill(false);

        $chart->dataset(trans_choice('general.expenses', 2), 'line', array_values($expense))
            ->backgroundColor('#ef3232')
            ->color('#ef3232')
            ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line'
        ])
            ->fill(false);

        $chart->dataset(trans_choice('general.profits', 1), 'line', array_values($profit))
            ->backgroundColor('#6da252')
            ->color('#6da252')
            ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line'
        ])
            ->fill(false);

        return $this->view('projects::widgets.line_chart', [
            'chart' => $chart
        ]);
    }

    private function calculateTotals($type, $start, $end, $period, $project)
    {
        $totals = [];

        $date_format = 'Y-m';

        if ($period == 'month') {
            $n = 1;
            $start_date = $start->format($date_format);
            $end_date = $end->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $start->quarter;
            $end_date = $end->quarter;
            $next_date = $start_date;
        }

        $s = clone $start;

        // $totals[$start_date] = 0;
        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        if ($project) {
            if ($type === 'income') {
                $ids = $project->revenues()->pluck('revenue_id');
                $revenues = collect($this->applyFilters(Transaction::type($type)->whereIn('id', $ids)->whereBetween('paid_at', [$start, $end])->isNotTransfer()->isNotDocument())->get());
                $ids = $project->invoices()->pluck('invoice_id');
                $invoices = collect($this->applyFilters(Invoice::accrued()->whereIn('id', $ids)->whereBetween('invoiced_at', [$start, $end]))->get());
                $items = $revenues->merge($invoices);
            }
            else {
                $ids = $project->payments()->pluck('payment_id');
                $payments = $this->applyFilters(Transaction::type($type)->whereIn('id', $ids)->whereBetween('paid_at', [$start, $end])->isNotTransfer()->isNotDocument())->get();
                $ids = $project->bills()->pluck('bill_id');
                $bills = collect($this->applyFilters(Bill::accrued()->whereIn('id', $ids)->whereBetween('billed_at', [$start, $end]))->get());
                $items = $payments->merge($bills);
            }
        } else {
            if ($type === 'income') {
                $ids = ProjectRevenue::where('company_id', session('company_id'))->pluck('revenue_id');
                $revenues = $this->applyFilters(Transaction::type($type)->whereIn('id', $ids)->isNotTransfer()->isNotDocument(), ['date_field' => 'paid_at'])->get();
                $ids = ProjectInvoice::where('company_id', session('company_id'))->pluck('invoice_id');
                $invoices = $this->applyFilters(Invoice::accrued()->whereIn('id', $ids), ['date_field' => 'invoiced_at'])->get();
                $items = $revenues->merge($invoices);
            }
            else {
                $ids = ProjectPayment::where('company_id', session('company_id'))->pluck('payment_id');
                $payments = $this->applyFilters(Transaction::type($type)->whereIn('id', $ids)->isNotTransfer()->isNotDocument(), ['date_field' => 'paid_at'])->get();
                $ids = ProjectBill::where('company_id', session('company_id'))->pluck('bill_id');
                $bills = $this->applyFilters(Bill::accrued()->whereIn('id', $ids), ['date_field' => 'billed_at'])->get();
                $items = $payments->merge($bills);
            }
        }

        $this->setTotals($totals, $items, $date_format, $period);

        return $totals;
    }

    private function setTotals(&$totals, $items, $date_format, $period)
    {
        foreach ($items as $item) {
            if ($period == 'month') {
                if ($item instanceof \App\Models\Banking\Transaction) {
                    $i = Date::parse($item->paid_at)->format($date_format);
                } elseif ($item instanceof \App\Models\Sale\Invoice) {
                    $i = Date::parse($item->invoiced_at)->format($date_format);
                } else {
                    $i = Date::parse($item->billed_at)->format($date_format);
                }
            } else {
                $i = Date::parse($item->paid_at)->quarter;
                if ($item instanceof \App\Models\Banking\Transaction) {
                    $i = Date::parse($item->paid_at)->quarter;
                } elseif ($item instanceof \App\Models\Sale\Invoice) {
                    $i = Date::parse($item->invoiced_at)->quarter;
                } else {
                    $i = Date::parse($item->billed_at)->quarter;
                }
            }

            if (! isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
        }
    }

    private function calculateProfit($incomes, $expenses)
    {
        $profit = [];

        foreach ($incomes as $key => $income) {
            if ($income > 0 && $income > $expenses[$key]) {
                $profit[$key] = $income - $expenses[$key];
            } else {
                $profit[$key] = 0;
            }
        }

        return $profit;
    }
}
