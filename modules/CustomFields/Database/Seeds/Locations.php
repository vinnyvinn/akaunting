<?php

namespace Modules\CustomFields\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\CustomFields\Models\Location;

class Locations extends Seeder
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
            /*[
                'name' => 'Permissions',
                'code' => 'auth.permissions',
            ],
            [
                'name' => 'Roles',
                'code' => 'auth.roles',
            ],
            [
                'name' => 'Users',
                'code' => 'auth.users',
            ],*/
            /*----------------------------------*/
            [
                'name' => 'Companies',
                'code' => 'common.companies',
            ],
            [
                'name' => 'Items',
                'code' => 'common.items',
            ],
            /*----------------------------------*/
            [
                'name' => 'Invoices',
                'code' => 'sales.invoices',
            ],
            [
                'name' => 'Revenues',
                'code' => 'sales.revenues',
            ],
            [
                'name' => 'Customers',
                'code' => 'sales.customers',
            ],
            /*----------------------------------*/
            [
                'name' => 'Bills',
                'code' => 'purchases.bills',
            ],
            [
                'name' => 'Payments',
                'code' => 'purchases.payments',
            ],
            [
                'name' => 'Vendors',
                'code' => 'purchases.vendors',
            ],
            /*----------------------------------*/
            [
                'name' => 'Accounts',
                'code' => 'banking.accounts',
            ],
            [
                'name' => 'Transfers',
                'code' => 'banking.transfers',
            ],
        ];

        foreach ($rows as $row) {
            Location::firstOrCreate($row);
        }
    }
}
