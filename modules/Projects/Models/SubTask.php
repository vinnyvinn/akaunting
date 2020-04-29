<?php
namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_sub_tasks';

    protected $fillable = [];

    public function task()
    {
        return $this->belongsTo('Modules\Projects\Models\Task');
    }

    public function users()
    {
        return $this->hasMany('Modules\Projects\Models\ProjectSubtaskUser', 'subtask_id');
    }
    
}
