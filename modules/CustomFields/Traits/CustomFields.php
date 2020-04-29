<?php

namespace Modules\CustomFields\Traits;

use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\Location;

trait CustomFields
{

    public function getFieldsByLocation($location)
    {
        $location = Location::where('code', $location)->first();

        return Field::enabled()->orderBy('name')->byLocation($location->id)->get();
    }

    public function getFieldById($id)
    {
        return Field::find($id);
    }
}
