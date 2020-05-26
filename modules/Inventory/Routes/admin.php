<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Inventory\Http\Controllers'
], function () {
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', 'InventoryController@index');

        Route::get('item-groups/autocomplete', 'ItemGroups@autocomplete')->name('item-groups.autocomplete');
        Route::get('item-groups/addItem', 'ItemGroups@addItem')->name('item-groups.add-item');
        Route::get('item-groups/addOption', 'ItemGroups@addOption')->name('item-groups.add-option');
        Route::get('item-groups/getOptionValues/{option_id}', 'ItemGroups@getOptionValues')->name('item-groups.get-option-values');
        Route::get('item-groups/{item_group}/duplicate', 'ItemGroups@duplicate')->name('item-groups.duplicate');
        Route::post('item-groups/import', 'ItemGroups@import')->name('item-groups.import');
        Route::get('item-groups/export', 'ItemGroups@export')->name('item-groups.export');
        Route::get('item-groups/{item_group}/enable', 'ItemGroups@enable')->name('item-groups.enable');
        Route::get('item-groups/{item_group}/disable', 'ItemGroups@disable')->name('item-groups.disable');
        Route::resource('item-groups', 'ItemGroups', ['middleware' => ['money']]);

        Route::get('items/{id}/details','Items@getDetails')->name('items.details');
        Route::resource('items', 'Items');

        Route::get('options/{option}/enable', 'Options@enable')->name('options.enable');
        Route::get('options/{option}/disable', 'Options@disable')->name('options.disable');
        Route::resource('options', 'Options');

        Route::get('manufacturers/{manufacturer}/enable', 'Manufacturers@enable')->name('manufacturers.enable');
        Route::get('manufacturers/{manufacturer}/disable', 'Manufacturers@disable')->name('manufacturers.disable');
        Route::resource('manufacturers', 'Manufacturers');

        Route::get('warehouses/{warehouse}/enable', 'Warehouses@enable')->name('warehouses.enable');
        Route::get('warehouses/{warehouse}/disable', 'Warehouses@disable')->name('warehouses.disable');
        Route::get('warehouses/transfer', 'Warehouses@transferItems')->name('warehouses.transfer');
        Route::post('warehouses/transfer/items', 'Warehouses@upadateItems')->name('warehouses.transfer.items');
        Route::post('warehouses/{warehouse}/details', 'Warehouses@getDetails')->name('warehouses.details');
        Route::resource('warehouses', 'Warehouses');


        Route::get('histories/print', 'Histories@print')->name('histories.print');
        Route::get('histories/transactions', 'Histories@transactions')->name('histories.transactions');
        Route::get('histories/export', 'Histories@export')->name('histories.export');
        Route::resource('histories', 'Histories');

        Route::get('invoice/item/autocomplete', 'Items@autocomplete')->name('inventory.invoice.item.autocomplete');
        Route::get('bill/item/autocomplete', 'Items@autocomplete')->name('inventory.bill.item.autocomplete');
    });
});

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Inventory\Http\Controllers'
], function () {
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('settings', 'Settings@edit')->name('inventory.settings.edit');
        Route::post('settings', 'Settings@update')->name('inventory.settings.update');
    });
});

