<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSubtaskUser extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_subtask_users';
    
    protected $fillable = ['company_id', 'project_id', 'task_id', 'subtask_id', 'user_id'];
}
