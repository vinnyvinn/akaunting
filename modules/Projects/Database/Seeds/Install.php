<?php

namespace Modules\Projects\Database\Seeds;

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
        $this->call(Dashboards::class);
        $this->call(Permissions::class);
        $this->call(Reports::class);
    }
}
