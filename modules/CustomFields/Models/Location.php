<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;
use Modules\CustomFields\Scopes\Company;

class Location extends Model
{
    protected $table = 'custom_fields_locations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code'];

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
