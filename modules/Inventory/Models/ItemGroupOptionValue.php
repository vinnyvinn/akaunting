<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class ItemGroupOptionValue extends Model
{
    use Cloneable, Notifiable;

    protected $table = 'inventory_item_group_option_values';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_group_id', 'item_group_option_id', 'option_id', 'option_value_id'];

    public function value()
    {
        return $this->belongsTo('Modules\Inventory\Models\OptionValue');
    }
}
