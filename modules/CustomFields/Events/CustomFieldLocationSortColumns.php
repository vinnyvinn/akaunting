<?php

namespace Modules\CustomFields\Events;

class CustomFieldLocationSortColumns
{
    public $code;

    public $columns;

    /**
     * Create a new event instance.
     *
     * @param $code
     * @param $columns
     *
     */
    public function __construct($code, $columns)
    {
        $this->code = $code;
        $this->columns = $columns;
    }
}
