<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\DateTime;

class Log extends Model
{
    use DateTime;

    protected $table = 'crm_logs';

    protected $fillable = [
        'company_id',
        'created_by',
        'logable_id',
        'logable_type',
        'type',
        'date',
        'time',
        'subject',
        'description',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function logable()
    {
        return $this->morphTo();
    }

    public function scopeInstance($query)
    {
        return $query;
    }
}
