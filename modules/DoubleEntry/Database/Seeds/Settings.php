<?php

namespace Modules\DoubleEntry\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class Settings extends Seeder
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
        $company_id = $this->command->argument('company');

        setting()->setExtraColumns(['company_id' => $company_id]);
        setting()->forgetAll();
        setting()->load(true);

        setting()->set([
            'double-entry.accounts_receivable' => 120,
            'double-entry.accounts_payable' => 200,
            'double-entry.accounts_expenses' => 628,
            'double-entry.types_bank' => 6,
            'double-entry.types_tax' => 17,
        ]);

        setting()->save();
    }
}
