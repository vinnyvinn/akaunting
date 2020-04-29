<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;

class FieldTypeOption extends Model
{

    protected $table = 'custom_fields_field_type_options';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'field_id', 'type_id', 'value'];

    public function field()
    {
        return $this->belongsTo('Modules\CustomFields\Models\Field');
    }
}
