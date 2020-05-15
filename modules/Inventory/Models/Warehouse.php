<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
//use Illuminate\Database\Eloquent\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;

class Warehouse extends Model
{
    use Cloneable, Notifiable;

    protected $table = 'inventory_warehouses';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array     */
    protected $searchableColumns = [
        'name'    => 10,
        'email'   => 5,
        'phone'   => 2,
        'address' => 1,
    ];

    public function items()
    {
        return $this->hasMany('Modules\Inventory\Models\WarehouseItem');
    }
}
