<?php

use App\Models\Auth\User;
//use App\Models\Common\Contact as CoreContact;
use App\Utilities\Date;
use Faker\Generator as Faker;
use Modules\Crm\Models\Contact;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Contact::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    $types = (string) setting('contact.type.customer', 'crm_contact');

    $contacts = Contact::enabled()->get();

    if ($contacts->count()) {
        $contact = $contacts->random(1)->first();
    } else {
        $contact = factory(Contact::class)->states('crm_contact')->create();
    }

    $contact_at = $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');
    $born_at = Date::parse($contact_at)->addDays(10)->format('Y-m-d');

    $stage  = ['customer', 'lead', 'opportunity', 'subscriber'];
    $source = ['advert', 'chat', 'contact_form', 'employee_referral',
               'external_referral', 'marketing_campaign', 'newsletter', 'online_store',
               'optin_form', 'partner', 'phone', 'public_relations',
               'sales_mail_alias', 'search_engine', 'seminar_internal', 'seminar_partner',
               'social_media', 'trade_show', 'web_download', 'web_research'];

    return [
        'type' => 'crm_contact',
        'name' => $faker->name,
        'company_id' => $company->id,
        'created_by' => 1,
        'contact_id' => $contact->id,
        'born_at' =>  $born_at,
        'stage' => $faker->randomElement($stage),
        'owner_id' => 1,
        'mobile' => $faker->phoneNumber,
        'fax_number' => $faker->phoneNumber,
        'source' => $faker->randomElement($source),
        'note' => $faker->text(5),
        'email' => $faker->email,
        'currency_code' => setting('default.currency'),
        'enabled' => $faker->boolean ? 1 : 0,
        'phone' => $faker->phoneNumber,
    ];
});

