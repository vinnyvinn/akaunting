<?php

namespace Modules\Crm\Database\Seeds;

use App\Abstracts\Model;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Seeder;
use Modules\Crm\Models\Contact;
use Module;

class SampleData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

      //  $count = (int) $this->command->option('count');
        $count = 10;

        $factory = Factory::construct(
            app(Faker::class),
            Module::getModulePath('crm') . '/Database/Factories'
        );

        $factory->of(Contact::class)->times($count)->create();

        Model::unguard();
    }
}
