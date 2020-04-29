<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealActivity extends Model
{
    protected $table = 'crm_deal_activities';

    protected $fillable = [
        'company_id',
        'created_by',
        'deal_id',
        'activity_type',
        'name',
        'date',
        'time',
        'duration',
        'assigned',
        'note',
        'done',
    ];

    public function deal()
    {
        return $this->hasOne('Modules\Crm\Models\Deal');
    }

    public function activityType()
    {
        return $this->hasOne('Modules\Crm\Models\DealActivityType', 'id', 'activity_type');
    }

    public function assigned()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'assigned_id');
    }

    public function getAssign($value)
    {
        return  \App\Models\Common\Company::find(session('company_id'))->users()->where('id',$value)->first();
    }
}
