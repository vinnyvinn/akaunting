<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use App\Traits\Media;
use Illuminate\Notifications\Notifiable;
use App\Traits\DateTime;

class Contact extends Model
{
    use Cloneable, Media, Notifiable, DateTime;

    protected $table = 'crm_contacts';

    protected $fillable = [
        'company_id',
        'contact_id',
        'created_by',
        'born_at',
        'stage',
        'owner_id',
        'mobile',
        'fax_number',
        'source',
        'note',
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

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\Auth\User', 'owner_id', 'id');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->contact->name;
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

    public function contactCompany()
    {
        return $this->hasMany('Modules\Crm\Models\CompanyContact', 'crm_contact_id', 'id');
    }

    public function notes()
    {
        return $this->morphMany('Modules\Crm\Models\Note', 'noteable');
    }

    public function emails()
    {
        return $this->morphMany('Modules\Crm\Models\Email', 'emailable');
    }

    public function logs()
    {
        return $this->morphMany('Modules\Crm\Models\Log', 'logable');
    }

    public function schedules()
    {
        return $this->morphMany('Modules\Crm\Models\Schedule', 'scheduleable');
    }

    public function tasks()
    {
        return $this->morphMany('Modules\Crm\Models\Task', 'taskable');
    }

    public function deals()
    {
        return $this->hasMany('Modules\Crm\Models\Deal', 'crm_contact_id', 'id');
    }

    public function scopeInstance($query)
    {
        return $query;
    }

    public function scopeEnabled($query)
    {
        return $query->join('contacts', 'crm_contacts.contact_id', '=', 'contacts.id')->where('contacts.enabled', 1)->select('crm_contacts.*');
    }

    public function scopeDisabled($query)
    {
        return $query->join('contacts', 'crm_contacts.contact_id', '=', 'contacts.id')->where('contacts.enabled', 0)->select('crm_contacts.*');
    }
}
