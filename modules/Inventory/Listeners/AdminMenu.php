<?php

namespace Modules\Inventory\Listeners;

use App\Events\Menu\AdminCreated as Event;
use App\Models\Module\Module;

class AdminMenu
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $modules = Module::all()->pluck('alias')->toArray();

        if (!in_array('inventory', $modules)) {
            return false;
        }

        $user = user();

        if (!user()->can([
            'read-inventory-item-groups',
            'read-common-items',
            'read-inventory-options',
            'read-inventory-manufacturers',
            'read-inventory-warehouses',
        ])) {
            return;
        }

        $attr = [];

        $event->menu->removeByTitle(trans_choice('general.items', 2));

        $event->menu->dropdown(trans('inventory::general.menu.inventory'), function ($sub) use ($user, $attr) {

            if ($user->can('read-common-items')) {
                $sub->url('inventory/items', trans_choice('general.items', 2), 1, $attr);
            }

            if ($user->can('read-inventory-item-groups')) {
                $sub->url('inventory/item-groups', trans('inventory::general.menu.item_groups'), 2, $attr);
            }

            if ($user->can('read-inventory-options')) {
                $sub->url('inventory/options', trans('inventory::general.menu.options'), 3, $attr);
            }

            /*
            if ($user->can('read-inventory-manufacturers')) {
                $sub->url('inventory/manufacturers', trans('inventory::general.menu.manufacturers'), 4, $attr);
            }
            */

            if ($user->can('read-inventory-warehouses')) {
                $sub->url('inventory/warehouses', trans('inventory::general.menu.warehouses'), 5, $attr);
            }

            if ($user->can('read-inventory-histories')) {
                $sub->url('inventory/histories', trans('inventory::general.menu.histories'), 6, $attr);
                $sub->url('inventory/histories/transactions', 'Transaction Histories', 5, $attr);
            }

            /*
            if ($user->can('read-inventory-reports')) {
                $sub->url('inventory/reports', trans('inventory::general.menu.reports'), 7, $attr);
            }


            if ($user->can('read-inventory-settings')) {
                $sub->url('inventory/settings', trans_choice('general.settings', 2), 8, $attr);
            }
            */
        }, 2.5, [
            'title' => trans('inventory::general.title'),
            'icon' => 'fa fa-cubes',
        ]);
    }
}
