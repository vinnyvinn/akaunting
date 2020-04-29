<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectInvoice extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_invoices';

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['company_id', 'project_id', 'invoice_id'];

    public function invoice()
    {
        return $this->hasOne('App\Models\Sale\Invoice', 'id');
    }
}
