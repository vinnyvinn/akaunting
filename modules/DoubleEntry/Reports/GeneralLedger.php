<?php

namespace Modules\DoubleEntry\Reports;

use App\Abstracts\Report;
use Modules\DoubleEntry\Models\Account as Coa;

class GeneralLedger extends Report
{
    public $default_name = 'double-entry::general.general_ledger';

    public $category = 'general.accounting';

    public $icon = 'fa fa-balance-scale';

    public function getGrandTotal()
    {
        return trans('general.na');
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['show'] = 'double-entry::general_ledger.show';
        $this->views['content'] = 'double-entry::general_ledger.content';
    }

    public function setData()
    {
        $limit = request('limit', setting('default.list_limit', '25'));

        $model = $this->applyFilters(Coa::with(['type', 'ledgers']));

        $accounts = $model->orderBy('code')->get()->each(function ($account) use ($limit) {
            $account->transactions = $account->ledgers()->orderBy('issued_at')->paginate($limit);
        });

        $this->de_accounts = $accounts;
    }

    public function setTables()
    {
        //
    }

    public function setDates()
    {
        //
    }

    public function setRows()
    {
        //
    }

    public function getFields()
    {
        return [];
    }
}
