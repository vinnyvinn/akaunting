<?php

namespace Modules\Inventory\Listeners;

use App\Events\Module\SettingShowing as Event;

class ShowSetting
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->modules->settings['inventory'] = [
            'name' => trans('inventory::general.name'),
            'description' => trans('inventory::general.description'),
            'url' => route('inventory.settings.edit'),
            'icon' => 'fa fa-cubes',
        ];
    }
}
