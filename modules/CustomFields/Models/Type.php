<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;
use Modules\CustomFields\Scopes\Company;

class Type extends Model
{

    protected $table = 'custom_fields_types';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type'];

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
}
