<?php

namespace Modules\Crm\Imports;

use App\Abstracts\Import;
use Modules\Crm\Models\Deal as Model;
use Modules\Crm\Http\Requests\Deal as Request;

class Deals extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
