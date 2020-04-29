<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;

class AccountBank extends Model
{
    protected $table = 'double_entry_account_bank';

    protected $fillable = ['company_id', 'account_id', 'bank_id'];

    public function account()
    {
        return $this->belongsTo('Modules\DoubleEntry\Models\Account', 'account_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Banking\Account', 'bank_id', 'id');
    }
}
