<?php
namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_tasks';

    protected $fillable = [];

    public function subtasks()
    {
        return $this->hasMany('Modules\Projects\Models\SubTask', 'task_id');
    }
}
