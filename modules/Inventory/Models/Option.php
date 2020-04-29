<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class Option extends Model
{
    use Cloneable, Notifiable;

    protected $table = 'inventory_options';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'type', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'type', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array     */
    protected $searchableColumns = [
        'name' => 10,
        'type' => 5
    ];

    public function values()
    {
        return $this->hasMany('Modules\Inventory\Models\OptionValue');
    }
}
