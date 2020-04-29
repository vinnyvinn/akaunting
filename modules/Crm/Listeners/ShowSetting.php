<?php

namespace Modules\Crm\Listeners;

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
        $event->modules->settings['crm'] = [
            'name' => trans('crm::general.name'),
            'description' => trans('crm::general.description'),
            'url' => route('crm.setting.edit'),
            'icon' => 'fa fa-handshake',
        ];
    }
}
