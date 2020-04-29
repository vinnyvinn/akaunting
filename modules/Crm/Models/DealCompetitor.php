<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealCompetitor extends Model
{
    protected $table = 'crm_deal_competitors';

    protected $fillable = [
        'company_id',
        'created_by',
        'deal_id',
        'name',
        'web_site',
        'strengths',
        'weaknesses'
    ];

    public function deal()
    {
        return $this->hasOne('Modules\Crm\Models\Deal');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }
}
