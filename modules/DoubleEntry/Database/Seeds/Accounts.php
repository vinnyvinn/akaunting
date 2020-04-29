<?php

namespace Modules\DoubleEntry\Database\Seeds;

use App\Abstracts\Model;
use Modules\DoubleEntry\Models\Account;
use Illuminate\Database\Seeder;

class Accounts extends Seeder
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

        $rows = [
            [
                'company_id' => $company_id,
                'type_id' => '1',
                'code' => '120',
                'name' => 'double-entry::accounts.120',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '3',
                'code' => '140',
                'name' => 'double-entry::accounts.140',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '2',
                'code' => '150',
                'name' => 'double-entry::accounts.150',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '2',
                'code' => '151',
                'name' => 'double-entry::accounts.151',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '2',
                'code' => '160',
                'name' => 'double-entry::accounts.160',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '2',
                'code' => '161',
                'name' => 'double-entry::accounts.161',
            ],
            /*[
                'company_id' => $company_id,
                'type_id' => '6',
                'code' => '090',
                'name' => 'Petty Cash',
            ],*/
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '200',
                'name' => 'double-entry::accounts.200',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '205',
                'name' => 'double-entry::accounts.205',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '210',
                'name' => 'double-entry::accounts.210',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '215',
                'name' => 'double-entry::accounts.215',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '216',
                'name' => 'double-entry::accounts.216',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '235',
                'name' => 'double-entry::accounts.235',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '236',
                'name' => 'double-entry::accounts.236',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '250',
                'name' => 'double-entry::accounts.250',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '255',
                'name' => 'double-entry::accounts.255',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '260',
                'name' => 'double-entry::accounts.260',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '835',
                'name' => 'double-entry::accounts.835',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '7',
                'code' => '855',
                'name' => 'double-entry::accounts.855',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '9',
                'code' => '290',
                'name' => 'double-entry::accounts.290',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '11',
                'code' => '500',
                'name' => 'double-entry::accounts.500',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '600',
                'name' => 'double-entry::accounts.600',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '605',
                'name' => 'double-entry::accounts.605',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '610',
                'name' => 'double-entry::accounts.610',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '615',
                'name' => 'double-entry::accounts.615',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '620',
                'name' => 'double-entry::accounts.620',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '624',
                'name' => 'double-entry::accounts.624',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '628',
                'name' => 'double-entry::accounts.628',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '632',
                'name' => 'double-entry::accounts.632',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '636',
                'name' => 'double-entry::accounts.636',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '640',
                'name' => 'double-entry::accounts.640',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '644',
                'name' => 'double-entry::accounts.644',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '648',
                'name' => 'double-entry::accounts.648',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '652',
                'name' => 'double-entry::accounts.652',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '656',
                'name' => 'double-entry::accounts.656',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '660',
                'name' => 'double-entry::accounts.660',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '664',
                'name' => 'double-entry::accounts.664',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '672',
                'name' => 'double-entry::accounts.672',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '676',
                'name' => 'double-entry::accounts.676',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '680',
                'name' => 'double-entry::accounts.680',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '684',
                'name' => 'double-entry::accounts.684',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '10',
                'code' => '700',
                'name' => 'double-entry::accounts.700',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '715',
                'name' => 'double-entry::accounts.715',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '800',
                'name' => 'double-entry::accounts.800',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '810',
                'name' => 'double-entry::accounts.810',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '815',
                'name' => 'double-entry::accounts.815',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '820',
                'name' => 'double-entry::accounts.820',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '12',
                'code' => '825',
                'name' => 'double-entry::accounts.825',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '13',
                'code' => '400',
                'name' => 'double-entry::accounts.400',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '13',
                'code' => '460',
                'name' => 'double-entry::accounts.460',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '13',
                'code' => '470',
                'name' => 'double-entry::accounts.470',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '13',
                'code' => '475',
                'name' => 'double-entry::accounts.475',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '16',
                'code' => '300',
                'name' => 'double-entry::accounts.300',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '16',
                'code' => '310',
                'name' => 'double-entry::accounts.310',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '16',
                'code' => '320',
                'name' => 'double-entry::accounts.320',
            ],
            [
                'company_id' => $company_id,
                'type_id' => '16',
                'code' => '330',
                'name' => 'double-entry::accounts.330',
            ],
            /*[
                'company_id' => $company_id,
                'type_id' => '6',
                'code' => '092',
                'name' => 'Savings Account',
            ],*/
        ];

        foreach ($rows as $row) {
            Account::firstOrCreate($row);
        }
    }
}
