<?php

namespace App\Models\Purchase;

use App\Models\Common\Item;
use App\Abstracts\Model;
//use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Warehouse;

class InventoryTx extends Model
{
    protected $guarded =[];

    public function item()
    {
        return $this->belongsTo(Item::class,'stock_link','id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

}
