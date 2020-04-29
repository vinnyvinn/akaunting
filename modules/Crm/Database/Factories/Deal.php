<?php

use App\Models\Auth\User;
use App\Utilities\Date;
use Faker\Generator as Faker;
use Modules\Crm\Models\Deal;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Deal::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    $contacted_at = $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
    $closed_at = Date::parse($contacted_at)->addDays(10)->format('Y-m-d');

    return [
        'name' => $faker->name,
        'company_id' => $company->id,
        'created_by' => 1,
        'contact_id' => 1,
        'closed_at' =>  $closed_at,
        'stage_id' => 1,
        'owner_id' => 1,
        'currency_code' => setting('default.currency'),
        'crm_contact_id' => 1,
        'crm_company_id' => 1,
        'pipeline_id' => 1,
        'amount'=> $faker->randomFloat(2, 10, 20),
        'color' => '#6da252',
        'status' => 'open',
    ];
});

