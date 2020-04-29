<?php
namespace Modules\Projects\Models;

use App\Abstracts\Model;

class Project extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    protected $dates = ['deleted_at', 'started_at', 'ended_at'];

    protected $fillable = ['company_id', 'name', 'description', 'customer_id', 'status', 'started_at', 'ended_at'];

    public function bills()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectBill', 'project_id');
    }

    public function invoices()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectInvoice', 'project_id');
    }

    public function payments()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectPayment', 'project_id');
    }

    public function revenues()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectRevenue', 'project_id');
    }

    public function income_transactions()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectRevenue', 'project_id')->whereHas('transaction', function ($query) {
            $query->whereNull('document_id');
        });
    }

    public function expense_transactions()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectPayment', 'project_id')->whereHas('transaction', function ($query) {
            $query->whereNull('document_id');
        });
    }
    
    public function tasks()
    {
        return $this->hasMany('Modules\Projects\Models\Task', 'project_id');
    }

    public function discussions()
    {
        return $this->hasMany('Modules\Projects\Models\Discussion', 'project_id');
    }

    public function users()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectUser', 'project_id');
    }
    
    public function activities()
    {
        return $this->hasMany('Modules\Projects\Models\Activity', 'project_id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Models\Common\Contact');
    }
}
