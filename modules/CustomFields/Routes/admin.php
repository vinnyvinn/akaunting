<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\CustomFields\Http\Controllers'
], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::group(['as' => 'custom-fields.','prefix' => 'custom-fields',], function () {

            Route::group(['as' => 'fields.','prefix' => 'fields'], function () {
                Route::get('getType', 'Fields@getType')->name('get.type');
                Route::get('getSortOrder', 'Fields@getSortOrder')->name('get.sort_order');
                Route::get('getLocation', 'Fields@getLocation')->name('get.location');

                Route::post('import', 'Fields@import')->name('import');
                Route::get('export', 'Fields@export')->name('export');

                Route::get('{field}/duplicate', 'Fields@duplicate')->name('duplicate');
                Route::get('{field}/enable', 'Fields@enable')->name('enable');
                Route::get('{field}/disable', 'Fields@disable')->name('disable');
            });

            Route::resource('fields', 'Fields');
        });
    });
});
