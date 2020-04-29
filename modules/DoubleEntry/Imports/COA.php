<?php

namespace Modules\DoubleEntry\Imports;

use App\Abstracts\Import;
use Modules\DoubleEntry\Http\Requests\Account as Request;
use Modules\DoubleEntry\Models\Account as Model;

class COA extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
