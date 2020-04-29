<?php

namespace Modules\Crm\Reports;

use App\Abstracts\Report;
use App\Traits\Charts;
use App\Utilities\Chartjs;
use Date;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Contact;

class Growth extends Report
{
    use Charts;

    public $default_name = 'crm::general.growth';

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
            'lead' => '#6da252',
            'customer' => '#328aef',
            'opportunity' => '#fb6340',
            'subscriber' => '#55588b',
        ];

        $chart->type($this->model->settings->chart)
            ->width((int) $config['width'])
            ->height((int) $config['height'])
            ->options($options)
            ->labels(!empty($config['dates']) ? array_values($config['dates']) : array_values($this->dates));

        foreach ($this->row_values['default'] as $type => $totals) {
            $chart->dataset(trans('crm::general.stage.' . $type), 'line', array_values($totals))
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
        $this->views['table.rows'] = 'crm::reports.growth.table.rows';
        $this->views['table.footer'] = 'crm::reports.growth.table.footer';
    }

        public function setRows()
    {
        $rows = [
            'lead' => trans('crm::general.stage.lead'),
            'customer' => trans('crm::general.stage.customer'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber'),
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
        $company_lead = $this->applyFilters(Company::where('stage', 'lead')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($company_lead, 'lead');

        $company_customer = $this->applyFilters(Company::where('stage', 'customer')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($company_customer, 'customer');

        $company_opportunity = $this->applyFilters(Company::where('stage', 'opportunity')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($company_opportunity, 'opportunity');

        $company_subscriber = $this->applyFilters(Company::where('stage', 'subscriber')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($company_subscriber, 'subscriber');

        $contact_lead = $this->applyFilters(Contact::where('stage', 'lead')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($contact_lead, 'lead');

        $contact_customer = $this->applyFilters(Contact::where('stage', 'customer')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($contact_customer, 'customer');

        $concact_opportunity = $this->applyFilters(Contact::where('stage', 'opportunity')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($concact_opportunity, 'opportunity');

        $contact_subscriber = $this->applyFilters(Contact::where('stage', 'subscriber')->instance(), ['date_field' => 'created_at'])->get();
        $this->setCrmTotals($contact_subscriber, 'subscriber');

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
