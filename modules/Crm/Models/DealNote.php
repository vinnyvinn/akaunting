<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealNote extends Model
{
    protected $table = 'crm_deal_notes';

    protected $fillable = [
        'company_id',
        'created_by',
        'deal_id',
        'crm_company_id',
        'note'
    ];

    public function deal()
    {
        return $this->hasOne('Modules\Crm\Models\Deal', 'id', 'deal_id');
    }
}
