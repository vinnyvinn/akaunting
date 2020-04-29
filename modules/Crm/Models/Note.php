<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\DateTime;

class Note extends Model
{
    use DateTime;

    protected $table = 'crm_notes';

    protected $fillable = [
        'company_id',
        'created_by',
        'noteable_id',
        'noteable_type',
        'message'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function noteable()
    {
        return $this->morphTo();
    }

    public function scopeInstance($query)
    {
        return $query;
    }
}
