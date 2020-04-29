<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;

class Deal extends Model
{
    use Currencies, Cloneable, Notifiable;

    protected $table = 'crm_deals';

    protected $fillable = [
        'company_id',
        'created_by',
        'crm_contact_id',
        'crm_company_id',
        'pipeline_id',
        'stage_id',
        'name',
        'amount',
        'owner_id',
        'color',
        'closed_at',
        'status' // open - won - lost - trash
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['owner'];

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

    public function owner()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'owner_id');
    }

    public function pipeline()
    {
        return $this->hasOne('Modules\Crm\Models\DealPipeline', 'id', 'pipeline_id');
    }

    public function notes()
    {
        return $this->morphMany('Modules\Crm\Models\Note', 'noteable');
    }

    public function emails()
    {
        return $this->morphMany('Modules\Crm\Models\Email', 'emailable');
    }

    public function activities()
    {
        return $this->hasMany('Modules\Crm\Models\DealActivity');
    }

    public function competitors()
    {
        return $this->hasMany('Modules\Crm\Models\DealCompetitor');
    }

    public function agents()
    {
        return $this->hasMany('Modules\Crm\Models\DealAgent');
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convertToDefault($this->amount, setting('default.currency'), 1, $format);
    }

    public function getAmountAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getPictureAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }
}
