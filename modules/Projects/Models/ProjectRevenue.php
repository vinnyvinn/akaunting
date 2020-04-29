<?php
namespace Modules\Projects\Models;

use App\Abstracts\Model;

class ProjectRevenue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_revenues';

    protected $dates = ['deleted_at'];

    protected $fillable = ['company_id', 'project_id', 'revenue_id'];

    public function transaction()
    {
        return $this->hasOne('App\Models\Banking\Transaction', 'id', 'revenue_id');
    }

    // @depreciated
    public function revenue()
    {
        return $this->transaction();
    }
}
