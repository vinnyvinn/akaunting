<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;

class AccountTax extends Model
{
    protected $table = 'double_entry_account_tax';

    protected $fillable = ['company_id', 'account_id', 'tax_id'];

    public function account()
    {
        return $this->belongsTo('Modules\DoubleEntry\Models\Account', 'account_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax', 'tax_id', 'id');
    }
}
