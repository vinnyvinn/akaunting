<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class Manufacturer extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_manufacturers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'description', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'description', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array     */
    protected $searchableColumns = [
        'name'        => 10,
        'description' => 5
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }

    public function manufacturer_vendors()
    {
        return $this->hasMany('Modules\Inventory\Models\ManufacturerVendor', 'manufacturer_id', 'id');
    }
}
