<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_comments';
    
    protected $fillable = [];

    public function discussion()
    {
        return $this->belongsTo('Modules\Projects\Models\Discussion');
    }
}
