<?php

namespace Modules\DoubleEntry\Database\Seeds;

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
        $this->call(Accounts::class);
        $this->call(Dashboards::class);
        $this->call(Reports::class);
        $this->call(Settings::class);

        $this->call(Classes::class);
        $this->call(Permissions::class);
        $this->call(Types::class);
    }
}
