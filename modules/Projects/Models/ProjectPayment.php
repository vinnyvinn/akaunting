<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPayment extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_payments';

    protected $dates = ['deleted_at'];

    protected $fillable = ['company_id', 'project_id', 'payment_id'];

    // @depreciated
    public function payment()
    {
        return $this->hasOne('App\Models\Banking\Transaction', 'id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Models\Banking\Transaction', 'id', 'payment_id');
    }
}
