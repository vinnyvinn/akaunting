<?php

namespace Modules\DoubleEntry\Listeners;

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
        $event->modules->settings['double-entry'] = [
            'name' => trans('double-entry::general.name'),
            'description' => trans('double-entry::general.description'),
            'url' => route('double-entry.settings.edit'),
            'icon' => 'fa fa-balance-scale',
        ];
    }
}
