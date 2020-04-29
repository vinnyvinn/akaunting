<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class WarehouseItem extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_warehouse_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'warehouse_id', 'item_id'];

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
}
