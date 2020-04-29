<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_discussions';
    
    protected $fillable = [];
    
    public function comments()
    {
        return $this->hasMany('Modules\Projects\Models\Comment', 'discussion_id');
    }

    public function likes()
    {
        return $this->hasMany('Modules\Projects\Models\DiscussionLike', 'discussion_id');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
    
    public function scopeSort($query, $field, $order)
    {
        return $query->orderBy($field, $order);
    }
}
