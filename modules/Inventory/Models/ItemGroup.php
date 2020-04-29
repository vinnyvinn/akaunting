<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class ItemGroup extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_item_groups';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['item_group_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'description', 'category_id', 'tax_id', 'enabled'];

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
        'sku'         => 5,
        'description' => 2,
    ];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['options', 'option_values'];

    public function values()
    {
        return $this->hasMany('Modules\Inventory\Models\OptionValue');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function options()
    {
        return $this->hasMany('Modules\Inventory\Models\ItemGroupOption');
    }

    public function option_values()
    {
        return $this->hasMany('Modules\Inventory\Models\ItemGroupOptionValue');
    }

    public function items()
    {
        return $this->hasMany('Modules\Inventory\Models\ItemGroupOptionItem');
    }
}
