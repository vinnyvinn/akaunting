<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBill extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_bills';

    protected $dates = ['deleted_at'];

    protected $fillable = ['company_id', 'project_id', 'bill_id'];
    
    public function bill()
    {
        return $this->hasOne('App\Models\Purchase\Bill', 'id');
    }
}
