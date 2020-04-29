<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealActivityType extends Model
{
    protected $table = 'crm_deal_activity_types';

    protected $fillable = [
        'company_id',
        'created_by',
        'name',
        'icon',
        'rank'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }
}
