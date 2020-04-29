<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class ItemGroupOptionItem extends Model
{
    use Cloneable,Notifiable;

    protected $table = 'inventory_item_group_option_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_id', 'option_id', 'option_value_id', 'item_group_id', 'item_group_option_id'];

    public function values()
    {
        return $this->hasMany('Modules\Inventory\Models\OptionValue');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }

    public function inventory_item()
    {
        return $this->hasOne('Modules\Inventory\Models\Item' ,'item_id','item_id');
    }
}
