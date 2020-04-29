<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_activities';
    
    protected $fillable = ['company_id', 'project_id', 'activity_id', 'activity_type', 'description', 'created_by'];
}
