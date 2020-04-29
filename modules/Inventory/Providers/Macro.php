<?php

namespace Modules\Inventory\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Models\Item;

class Macro extends ServiceProvider
{
    public function boot()
    {
        Builder::macro('getInventoryItem', function (int $item_id) {
            $item = Item::where('item_id', $item_id)->first();

            if (!empty($item)) {
                return $item;
            }

            return 0;
        });
    }
}
