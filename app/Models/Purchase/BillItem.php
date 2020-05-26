<?php

namespace App\Models\Purchase;

use App\Abstracts\Model;
//use Illuminate\Database\Eloquent\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\WarehouseItem;

class BillItem extends Model
{

    use Cloneable, Currencies;

    protected $table = 'bill_items';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['discount','quantity_r'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
   protected $guarded = [];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['taxes'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function($model) {
            $model->setTaxIds();
        });
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Inventory\Models\WarehouseItem', 'item_id', 'item_id');
    }
    public function bill()
    {
        return $this->belongsTo('App\Models\Purchase\Bill');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Purchase\BillItemTax', 'bill_item_id', 'id');
    }

    /**
     * Convert price to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (double) $value;
    }

    public function getQuantityRAttribute()
    {
        return $this->quantity_received;
    }
    /**
     * Convert total to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (double) $value;
    }

    /**
     * Convert tax to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setTaxAttribute($value)
    {
        $this->attributes['tax'] = (double) $value;
    }

    /**
     * Get the formatted discount.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        if (setting('localisation.percent_position', 'after') === 'after') {
            $text = ($this->discount_type === 'normal') ? $this->discount_rate . '%' : $this->discount_rate;
        } else {
            $text = ($this->discount_type === 'normal') ? '%' . $this->discount_rate : $this->discount_rate;
        }

        return $text;
    }

    /**
     * Get the formatted discount.
     *
     * @return string
     */
    public function getDiscountRateAttribute($value = 0)
    {
        $discount_rate = 0;

        switch (setting('localisation.discount_location', 'total')) {
            case 'no':
            case 'total':
                $discount_rate = 0;
                break;
            case 'item':
                $discount_rate = $value;
                break;
            case 'both':
                $discount_rate = $value;
                break;
        }

        return $discount_rate;
    }

    /**
     * Convert tax to Array.
     *
     * @return void
     */
    public function setTaxIds()
    {
        $tax_ids = [];

        foreach ($this->taxes as $tax) {
            $tax_ids[] = (string) $tax->tax_id;
        }

        $this->setAttribute('tax_id', $tax_ids);
    }

    public function onCloning($src, $child = null)
    {
        unset($this->tax_id);
    }
    public static function receiveQty($bill){
        \Log::info('iiiiiiiiiii');
        foreach (request()->get('items') as $item){
            foreach ($bill->items as $bill_item){
            if ($bill_item->id == $item['id']){
                if (($item['quantity_received'] + $bill_item->quantity_received) > $bill_item->quantity){
                    return -1;
                }
            }
            }
         if ($item['quantity_received'] <=0){
             return 0;
        }

        DB::table('bill_items')->where('id',$item['id'])->increment('quantity_received',$item['quantity_received']);
        DB::table('bill_items')->where('id',$item['id'])->update(['quantity_update'=>$item['quantity_received']]);
         WarehouseItem::where('item_id',BillItem::find($item['id'])->item_id)->where('warehouse_id',$bill->warehouse_id)->decrement('quantity',$item['quantity_received']);
        }
       Bill::updateTotal(self::find($item['id'])->bill_id,request()->get('total'));
       return 1;
    }
}
