<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class CompanyContact extends Model
{
    protected $table = 'crm_company_contacts';

    protected $fillable = [
        'company_id',
        'created_by',
        'crm_company_id',
        'crm_contact_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function company()
    {
        return $this->hasOne('Modules\Crm\Models\Company', 'id', 'crm_company_id');
    }

    public function contact()
    {
        return $this->hasOne('Modules\Crm\Models\Contact', 'id', 'crm_contact_id');
    }
}
