<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;

class Journal extends Model
{
    protected $table = 'double_entry_journals';

    protected $fillable = ['company_id', 'paid_at', 'amount', 'description', 'reference'];

    public function ledger()
    {
        return $this->morphOne('Modules\DoubleEntry\Models\Ledger', 'ledgerable');
    }

    public function ledgers()
    {
        return $this->morphMany('Modules\DoubleEntry\Models\Ledger', 'ledgerable');
    }
}
