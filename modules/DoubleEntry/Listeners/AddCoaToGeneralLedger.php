<?php

namespace Modules\DoubleEntry\Listeners;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use Modules\DoubleEntry\Models\Account as Coa;
use Modules\DoubleEntry\Models\Type;

class AddCoaToGeneralLedger extends Listener
{
    public $classes = [
        'Modules\DoubleEntry\Reports\GeneralLedger',
    ];

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterShowing(FilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $types = Type::pluck('name', 'id')->map(function ($name) {
            return trans($name);
        })->toArray();

        $de_accounts = [];
        Coa::with(['type'])->orderBy('code')->get()->each(function ($account) use($types, &$de_accounts) {
            $de_accounts[$types[$account->type_id]][$account->id] = trans($account->name);
        })->sort()->all();
        //ksort($de_accounts);

        $event->class->filters['de_accounts'] = $de_accounts;
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $de_accounts = request('de_accounts', []);

        if (empty($de_accounts)) {
            return;
        }

        $event->model->whereIn('id', (array) $de_accounts);
    }
}
