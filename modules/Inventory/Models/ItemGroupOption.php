<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class ItemGroupOption extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_item_group_options';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_group_id', 'option_id'];

    public function values()
    {
        return $this->hasMany('Modules\Inventory\Models\ItemGroupOptionValue');
    }

    public function option()
    {
        return $this->belongsTo('Modules\Inventory\Models\Option');
    }
}
