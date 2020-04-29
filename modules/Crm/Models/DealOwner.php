<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealOwner extends Model
{
    protected $table = 'crm_deal_owners';

    protected $fillable = [
        'company_id',
        'deal_id',
        'user_id',
    ];

    public function getUser()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }
}
