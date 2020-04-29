<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealAgent extends Model
{
    protected $table = 'crm_deal_agents';

    protected $fillable = [
        'company_id',
        'created_by',
        'deal_id',
        'user_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function deal()
    {
        return $this->belongsTo('App\Models\Common\Deal');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }
}
