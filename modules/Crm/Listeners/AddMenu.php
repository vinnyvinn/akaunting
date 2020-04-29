<?php

namespace Modules\Crm\Listeners;

use Auth;
use App\Events\Menu\AdminCreated as Event;

class AddMenu
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = Auth::user();

        if (!$user->can([
            'read-crm-activities',
            'read-crm-companies',
            'read-crm-contacts',
            'read-crm-deals',
            'read-crm-schedules',
        ])) {
            return;
        }

        $attr = [];

        $event->menu->dropdown(trans('crm::general.name'), function ($sub) use ($user, $attr) {
            if ($user->can('read-crm-contacts')) {
                $sub->url('crm/contacts', trans_choice('crm::general.contacts', 2), 1, $attr);
            }

            if ($user->can('read-crm-companies')) {
                $sub->url('crm/companies', trans_choice('crm::general.companies', 2), 2, $attr);
            }

            if ($user->can('read-crm-deals')) {
                $sub->url('crm/deals', trans_choice('crm::general.deals', 2), 3, $attr);
            }

            if ($user->can('read-crm-activities')) {
                $sub->url('crm/activities', trans_choice('crm::general.activities', 2), 4, $attr);
            }

            if ($user->can('read-crm-schedules')) {
                $sub->url('crm/schedules', trans_choice('crm::general.schedule', 2), 5, $attr);
            }
        }, 2, [
            'title' => trans('crm::general.name'),
            'icon' => 'fa fa-handshake',
        ]);
    }
}
