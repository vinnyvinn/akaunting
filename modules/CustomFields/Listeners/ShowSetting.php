<?php

namespace Modules\CustomFields\Listeners;

use App\Events\Module\SettingShowing as Event;

class ShowSetting
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->modules->settings['custom-fields'] = [
            'name' => trans('custom-fields::general.name'),
            'description' => trans('custom-fields::general.description'),
            'url' => 'settings/custom-fields/fields',
            'icon' => 'fa fa-angle-double-right',
        ];
    }
}
