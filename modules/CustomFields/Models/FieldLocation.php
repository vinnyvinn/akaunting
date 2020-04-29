<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;

class FieldLocation extends Model
{

    protected $table = 'custom_fields_field_locations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'field_id', 'location_id', 'sort_order'];

    public function field()
    {
        return $this->hasMany('Modules\CustomFields\Models\Field');
    }
}
