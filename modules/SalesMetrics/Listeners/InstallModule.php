<?php

namespace Modules\SalesMetrics\Listeners;

use App\Events\Module\Installed as Event;
use Illuminate\Support\Facades\Artisan;

class InstallModule
{
    public $alias = 'sales-metrics';

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->callSeeds();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\SalesMetrics\Database\Seeds\Install',
        ]);
    }
}
