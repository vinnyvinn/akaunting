<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\DateTime;

class Task extends Model
{
    use DateTime;

    protected $table = 'crm_tasks';

    protected $fillable = [
        'company_id',
        'created_by',
        'taskable_id',
        'taskable_type',
        'name',
        'description',
        'user_id',
        'started_at',
        'started_time_at',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    public function taskable()
    {
        return $this->morphTo();
    }

    public function scopeInstance($query)
    {
        return $query;
    }
}
