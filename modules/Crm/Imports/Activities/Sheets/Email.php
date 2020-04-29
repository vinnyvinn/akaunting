<?php

namespace Modules\Crm\Imports\Activities\Sheets;

use App\Abstracts\Import;

use Modules\Crm\Http\Requests\Email as Request;
use Modules\Crm\Models\Email as Model;

class Email extends Import
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
