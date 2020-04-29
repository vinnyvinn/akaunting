<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;

class ManufacturerVendor extends Model
{

    protected $table = 'inventory_manufacturer_vendors';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'manufacturer_id', 'vendor_id'];

    public function manufacturer()
    {
        return $this->belongsTo('Modules\Inventory\Models\Manufacturer');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Purchase\Vendor');
    }
}
