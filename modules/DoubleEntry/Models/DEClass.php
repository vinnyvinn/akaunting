<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;
use Modules\DoubleEntry\Scopes\Company;

class DEClass extends Model
{
    protected $table = 'double_entry_classes';

    protected $fillable = ['name'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new Company);
    }

    public function types()
    {
        return $this->hasMany('Modules\DoubleEntry\Models\Type', 'class_id');
    }

    public function accounts()
    {
        return $this->hasManyThrough('Modules\DoubleEntry\Models\Account', 'Modules\DoubleEntry\Models\Type', 'class_id', 'type_id');
    }
}
