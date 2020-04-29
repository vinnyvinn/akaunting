<?php

namespace Modules\Crm\Reports;

use App\Abstracts\Report;
use App\Traits\Charts;
use App\Utilities\Chartjs;
use Date;
use Modules\Crm\Models\Email;
use Modules\Crm\Models\Log;
use Modules\Crm\Models\Note;
use Modules\Crm\Models\Schedule;
use Modules\Crm\Models\Task;

class Activity extends Report
{
    use Charts;

    public $default_name = 'crm::general.activity';

    public $category = 'crm::general.name';

    public $icon = 'fa fa-handshake';

    public function getGrandTotal()
    {
        if (!$this->loaded) {
            $this->load();
        }

        if (!empty($this->footer_totals)) {
            $grand_total = 0;

            foreach ($this->footer_totals as $total) {
                $grand_total += is_array($total) ? array_sum($total) : $total;
            }
        } else {
            $grand_total = trans('general.na');
        }

        return $grand_total;
    }

    public function getChart()
    {
        $chart = new Chartjs();

        if (empty($this->model->settings->chart)) {
            return $chart;
        }

        $config = $this->chart[$this->model->settings->chart];

        $default_options = $this->getLineChartOptions();

        $config_options = array_merge((array) $config['options'], ['legend' => ['display' => true]]);

        $options = array_merge($default_options, $config_options);

        $colors = [
            'email' => '#6da252',
            'note' => '#328aef',
            'log' => '#fb6340',
            'schedule' => '#55588b',
            'task' => '#ef3232',
        ];

        $chart->type($this->model->settings->chart)
            ->width((int) $config['width'])
            ->height((int) $config['height'])
            ->options($options)
            ->labels(!empty($config['dates']) ? array_values($config['dates']) : array_values($this->dates));

        foreach ($this->row_values['default'] as $type => $totals) {
            $chart->dataset(trans('crm::general.' . $type), 'line', array_values($totals))
                ->backgroundColor($colors[$type])
                ->color($colors[$type])
                ->options([
                    'borderWidth' => 4,
                    'pointStyle' => 'line',
                ])
                ->fill(false);
        }

        return $chart;
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['table.rows'] = 'crm::reports.activity.table.rows';
        $this->views['table.footer'] = 'crm::reports.activity.table.footer';
    }

    public function setRows()
    {
        $rows = [
            'email' => trans('crm::general.email'),
            'note' => trans('crm::general.note'),
            'log' => trans('crm::general.log'),
            'schedule' => trans('crm::general.schedule'),
            'task' => trans('crm::general.task'),
        ];

        foreach ($this->dates as $date) {
            foreach ($this->tables as $table) {
                foreach ($rows as $id => $name) {
                    $this->row_names[$table][$id] = $name;
                    $this->row_values[$table][$id][$date] = 0;
                }

                $this->footer_totals[$table][$date] = 0;
            }
        }
    }

    public function setData()
    {
        $emails = $this->applyFilters(Email::instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($emails, 'email');

        $notes = $this->applyFilters(Note::instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($notes, 'note');

        $logs = $this->applyFilters(Log::instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($logs, 'log');

        $schedules = $this->applyFilters(Schedule::instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($schedules, 'schedule');

        $tasks = $this->applyFilters(Task::instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($tasks, 'task');
    }

    public function setCrmTotals($items, $type)
    {
        foreach ($items as $item) {
            $date = $this->getFormattedDate(Date::parse($item->created_at));

            $this->row_values['default'][$type][$date]++;

            $this->footer_totals['default'][$date]++;
        }
    }

    public function getFields()
    {
        return [
            $this->getPeriodField(),
        ];
    }
}
