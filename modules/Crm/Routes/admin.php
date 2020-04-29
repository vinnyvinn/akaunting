<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Crm\Http\Controllers'
], function () {
    Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
        //Activities
        Route::get('activities', 'Activities@index')->name('activities.index');
        Route::post('activities/import', 'Activities@import')->name('activities.import');
        Route::get('activities/export', 'Activities@export')->name('activities.export');

        // Contacts
        Route::get('contacts/{contact}/duplicate', 'Contacts@duplicate')->name('contacts.duplicate');
        Route::post('contacts/import', 'Contacts@import')->name('contacts.import');
        Route::get('contacts/export', 'Contacts@export')->name('contacts.export');
        Route::get('contacts/{contact}/enable', 'Contacts@enable')->name('contacts.enable');
        Route::get('contacts/{contact}/disable', 'Contacts@disable')->name('contacts.disable');
        Route::resource('contacts', 'Contacts');

        // Companies
        Route::get('companies/{company}/duplicate', 'Companies@duplicate')->name('companies.duplicate');
        Route::post('companies/import', 'Companies@import')->name('companies.import');
        Route::get('companies/export', 'Companies@export')->name('companies.export');
        Route::get('companies/{company}/enable', 'Companies@enable')->name('companies.enable');
        Route::get('companies/{company}/disable', 'Companies@disable')->name('companies.disable');
        Route::resource('companies', 'Companies');

        // Deals
        Route::get('deals/{deal}/activities/{activity}/done', 'Deals@done')->name('deal.activities.done');
        Route::get('deals/{deal}/stages/{stage}', 'Deals@stage')->name('deals.stage');
        Route::get('deals/{deal}/won', 'Deals@won')->name('deals.won');
        Route::get('deals/{deal}/lost', 'Deals@lost')->name('deals.lost');
        Route::get('deals/{deal}/trash', 'Deals@trash')->name('deals.trash');
        Route::get('deals/{deal}/reopen', 'Deals@reopen')->name('deals.reopen');
        Route::get('deals/{deal}/duplicate', 'Deals@duplicate')->name('deals.duplicate');
        Route::post('deals/import', 'Deals@import')->name('deals.import');
        Route::get('deals/export', 'Deals@export')->name('deals.export');
        Route::get('deals/{deal}/enable', 'Deals@enable')->name('deals.enable');
        Route::get('deals/{deal}/disable', 'Deals@disable')->name('deals.disable');
        Route::resource('deals', 'Deals');

        // Modals
        Route::get('modals/deals/{deal}/owners/{owner}/change', 'Modals\Deals@ownerEdit')->name('modals.deals.owner.change.edit');
        Route::patch('modals/deals/{deal}/owner/{owner}/change', 'Modals\Deals@ownerUpdate')->name('modals.deals.owner.change.update');
        Route::get('modals/deals/{deal}/companies/{company}/change', 'Modals\Deals@companyEdit')->name('modals.deals.owner.company.edit');
        Route::patch('modals/deals/{deal}/companies/{company}/change', 'Modals\Deals@companyUpdate')->name('modals.deals.owner.company.update');
        Route::patch('modals/deals/{deal}/contacts/{contact}/change', 'Modals\Deals@contactUpdate')->name('modals.deals.owner.contact.update');
        Route::get('modals/deals/{deal}/contacts/{contact}/change', 'Modals\Deals@contactEdit')->name('modals.deals.owner.contact.edit');
        Route::post('modals/deals/{deal}/competitors/{compitetor}', 'Modals\DealCompetitors@update')->name('modals.deal.competitors.update');

        Route::resource('modals/deals/{deal}/activities', 'Modals\DealActivities', ['names' => 'modals.deal.activities']);
        Route::resource('modals/deals/{deal}/agents', 'Modals\DealAgents', ['names' => 'modals.deal.agents']);
        Route::resource('modals/deals/{deal}/competitors', 'Modals\DealCompetitors', ['names' => 'modals.deal.competitors']);
        Route::resource('modals/deals', 'Modals\Deals', ['names' => 'modals.deals', 'middleware' => 'money']);
        Route::resource('modals/contact/{contact}/company', 'Modals\ContactCompany', ['names' => 'modals.contacts']);
        Route::resource('modals/company/{company}/contact', 'Modals\CompanyContact', ['names' => 'modals.companies']);
        Route::resource('modals/activities/{type}/{type_id}/notes', 'Modals\Notes', ['names' => 'modals.notes']);
        Route::resource('modals/activities/{type}/{type_id}/emails', 'Modals\Emails', ['names' => 'modals.emails']);
        Route::resource('modals/activities/{type}/{type_id}/logs', 'Modals\Logs', ['names' => 'modals.logs']);
        Route::resource('modals/activities/{type}/{type_id}/schedules', 'Modals\Schedules', ['names' => 'modals.schedules']);
        Route::resource('modals/activities/{type}/{type_id}/tasks', 'Modals\Tasks', ['names' => 'modals.tasks']);
        Route::resource('modals/activities/{type}/{type_id}/deals', 'Modals\Activities', ['names' => 'modals.deal-activities']);

        //Schedules
        Route::get('schedules', 'Schedules@index')->name('schedules.index');
    });
});


Route::group([
    'middleware' => 'admin',
    'prefix' => 'crm','as' => 'crm.',
    'namespace' => 'Modules\Crm\Http\Controllers'
], function () {
    //Companies
    Route::get('company/{company}', 'Companies@getContact')->name('get.contact');
    Route::post('company/create', 'Companies@addContact')->name('add.Contact');
    Route::delete('company/delete/contact/{id}', 'Companies@destroyContact')->name('destroy.contact.company');

    //Contacts
    Route::get('contact/company/{contact}', 'Contacts@getCompany')->name('get.contact.company');
    Route::post('contacts/create/company', 'Contacts@addCompany')->name('add.Company');
    Route::delete('contacts/delete/company/{id}', 'Contacts@destroyCompany')->name('destroy.Company');

    //Email
    Route::get('contacts/meeting/{contact}', 'Contacts@getMeeting')->name('get.contact.meeting');
    Route::get('contacts/email/{contact}', 'Contacts@getEmail')->name('get.contact.email');

    Route::get('companies/meeting/{company}', 'Companies@getMeeting')->name('get.company.meeting');
    Route::get('companies/email/{company}', 'Companies@getEmail')->name('get.company.email');

      //Settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'Settings@edit')->name('setting.edit');
        Route::patch('/', 'Settings@update');
        Route::get('activity', 'Settings@getActivityType')->name('get.setting.activity');
        Route::post('activity/create', 'Settings@createActivityType')->name('get.setting.activity.create');

        Route::get('activity/edit/{activity}', 'Settings@editActivityType')->name('get.setting.activity.edit');
        Route::post('activity/update/{activity}', 'Settings@storeActivityType')->name('get.setting.activity.store');
        Route::delete('activity/delete/{activity}', 'Settings@destroyActivityType')->name('get.setting.activity.destroy');

        Route::get('pipeline', 'Settings@getPipeline')->name('get.setting.pipeline');
        Route::post('pipeline/create', 'Settings@createPipeline')->name('setting.deal.pipeline.create');
        Route::delete('pipeline/delete/{pipeline}', 'Settings@destroyPipeline')->name('setting.deal.pipeline.delete');

        Route::get('stage/{pipeline}', 'Settings@getStage')->name('get.setting.stage');
        Route::post('stage/create/{pipeline}', 'Settings@createStage')->name('setting.deal.stage.create');
        Route::delete('stage/delete/{stage}', 'Settings@destroyStage')->name('setting.deal.stage.delete');
        Route::get('stage/edit/{stage}', 'Settings@editStage')->name('setting.deal.stage.edit');
        Route::post('stage/update/{stage}', 'Settings@storeStage')->name('setting.deal.stage.update');

        //rank update
        Route::post('activity/rank', 'Settings@activityRankUpdate')->name('setting.activity.rank.update');
        Route::post('stage/rank', 'Settings@stageRankUpdate')->name('setting.stage.rank.update');
    });
});
