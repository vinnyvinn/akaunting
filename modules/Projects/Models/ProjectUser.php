<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_users';
    
    protected $fillable = ['company_id', 'project_id', 'user_id'];
    
    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }
}
