<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->call(\Modules\CustomFields\Database\Seeds\Install::class);
      $this->call(\Database\Seeds\TestCompany::class);
    //  $this->call(\Database\Seeds\SampleData::class);
    }
}
