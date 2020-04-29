<?php

namespace Modules\Crm\Imports;

use App\Abstracts\Import;
use App\Models\Common\Contact as Model;
use Modules\Crm\Http\Requests\Contact as Request;

class Contacts extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'crm_contact';

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
