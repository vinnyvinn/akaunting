<?php
use App\Models\Auth\User;
use Modules\Crm\Models\Schedule;
use Faker\Generator as Faker;
use Jenssegers\Date\Date;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Schedule::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [];
});

