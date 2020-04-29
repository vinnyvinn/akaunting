<?php

namespace Modules\DoubleEntry\Database\Seeds;

use App\Abstracts\Model;
use Modules\DoubleEntry\Models\DEClass;
use Illuminate\Database\Seeder;

class Classes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $rows = [
            [
                'name' => 'double-entry::classes.assets',
            ],
            [
                'name' => 'double-entry::classes.liabilities',
            ],
            [
                'name' => 'double-entry::classes.expenses',
            ],
            [
                'name' => 'double-entry::classes.income',
            ],
            [
                'name' => 'double-entry::classes.equity',
            ],
        ];

        foreach ($rows as $row) {
            DEClass::firstOrCreate($row);
        }
    }
}
