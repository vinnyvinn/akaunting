<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;
use Modules\DoubleEntry\Scopes\Company;

class Type extends Model
{
    protected $table = 'double_entry_types';

    protected $fillable = ['class_id', 'name'];

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

    public function accounts()
    {
        return $this->hasMany('Modules\DoubleEntry\Models\Account');
    }

    public function declass()
    {
        return $this->belongsTo('Modules\DoubleEntry\Models\DEClass');
    }
}
