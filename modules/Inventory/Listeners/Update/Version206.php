<?php

namespace Modules\Inventory\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\Schema;

class Version206 extends Listener
{
    const ALIAS = 'inventory';

    const VERSION = '2.0.6';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        Artisan::call('company:seed', [
            'company' => session('company_id'),
            '--class' => 'Modules\Inventory\Database\Seeds\Reports',
        ]);

    }

    public function updateDatabase()
    {
        Schema::table('inventory_items', function ($table) {
            $table->string('sku')->nullable()->change();
        });
    }
}
