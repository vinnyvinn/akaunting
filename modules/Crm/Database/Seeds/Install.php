<?php

namespace Modules\Crm\Database\Seeds;

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
        $this->call(Pipelines::class);
        $this->call(Reports::class);

    }
}
