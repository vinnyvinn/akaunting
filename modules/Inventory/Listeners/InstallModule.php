<?php

namespace Modules\Inventory\Listeners;

use App\Events\Module\Installed as Event;
use Artisan;

class InstallModule
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'inventory') {
            return;
        }

        $this->callSeeds();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Inventory\Database\Seeds\Install',
        ]);
    }
}
