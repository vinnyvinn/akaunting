<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;

class Item extends Model
{
    use Cloneable, Notifiable;

    protected $table = 'inventory_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_id', 'sku', 'opening_stock', 'opening_stock_value', 'reorder_level', 'vendor_id'];

    public function values()
    {
        return $this->hasMany('Modules\Inventory\Models\OptionValue');
    }

    public function warehouse()
    {
        return $this->hasMany('Modules\Inventory\Models\WarehouseItem', 'item_id', 'item_id');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getWarehouseIdAttribute()
    {
        $item_warehouse = $this->belongsTo('Modules\Inventory\Models\WarehouseItem', 'item_id', 'item_id')->first();

        return isset($item_warehouse->warehouse_id) ? $item_warehouse->warehouse_id : null;
    }
}
