<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;

class ManufacturerItem extends Model
{

    protected $table = 'inventory_manufacturer_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'manufacturer_id', 'item_id'];

    public function manufacturer()
    {
        return $this->belongsTo('Modules\Inventory\Models\Manufacturer');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }
}
