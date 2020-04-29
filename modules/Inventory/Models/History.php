<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Models\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;


class History extends Model
{
    use Cloneable, Notifiable;

    protected $table = 'inventory_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'item_id', 'warehouse_id', 'type_id', 'type_type', 'quantity'];

    public function type()
    {
        return $this->morphTo();
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Inventory\Models\Warehouse');
    }

    public function getActionTextAttribute()
    {
        $types = explode("\\", $this->type_type);
        $type = end($types);

        switch ($type) {
            case 'Item':
                return '#' . $this->type->id;
                break;
            case 'InvoiceItem':
                return $this->type->invoice->invoice_number;
                break;
            case 'BillItem':
                return $this->type->bill->bill_number;
                break;
        }
    }

    public function getActionUrlAttribute()
    {
        $types = explode("\\", $this->type_type);
        $type = end($types);

        switch ($type) {
            case 'Item':
                return 'inventory/items/' . $this->type->id;
                break;
            case 'InvoiceItem':
                return 'sales/invoices/' . $this->type->invoice->id;
                break;
            case 'BillItem':
                return 'purchases/bills/' . $this->type->bill->id;
                break;
        }
    }
}
