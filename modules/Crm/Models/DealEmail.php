<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealEmail extends Model
{
    protected $table = 'crm_deal_emails';

    protected $fillable = [
        'company_id',
        'created_by',
        'deal_id',
        'to',
        'subject',
        'body'
    ];
    public function deal()
    {
        return $this->hasOne('Modules\Crm\Models\Deal', 'id', 'deal_id');
    }
}
