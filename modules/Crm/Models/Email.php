<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\DateTime;

class Email extends Model
{
    use DateTime;

    protected $table = 'crm_emails';

    protected $fillable = [
        'company_id',
        'created_by',
        'emailable_id',
        'emailable_type',
        'from',
        'to',
        'subject',
        'body'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function emailable()
    {
        return $this->morphTo();
    }

    public function scopeInstance($query)
    {
        return $query;
    }
}
