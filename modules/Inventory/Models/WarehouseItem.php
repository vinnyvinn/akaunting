<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;
use Faker\Factory;


class WarehouseItem extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_warehouse_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function stock()
    {
        return 100;
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Inventory\Models\Warehouse');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }
    public static function createWarehouse($inv,$item){
        $faker = Factory::create();
        self::create([
            'company_id' => $inv->company_id,
            'warehouse_id' => setting('inventory.default_warehouse'),
            'item_id' => $item->id,
            'quantity' => $faker->numberBetween(5,20)
        ]);
        History::createHistory($inv,$item);
    }
}
