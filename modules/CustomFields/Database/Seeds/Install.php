<?php

namespace Modules\CustomFields\Database\Seeds;

use Illuminate\Database\Seeder;

class Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Locations::class);
        $this->call(Permissions::class);
        $this->call(Types::class);
    }
}
