<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\DateTime;

class Schedule extends Model
{
    use DateTime;

    protected $table = 'crm_schedules';

    protected $fillable = [
        'company_id',
        'created_by',
        'scheduleable_id',
        'scheduleable_type',
        'type',
        'name',
        'description',
        'started_at',
        'started_time_at',
        'ended_at',
        'ended_time_at',
        'user_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    public function scheduleable()
    {
        return $this->morphTo();
    }

    public function scopeInstance($query)
    {
        return $query;
    }
}

