<?php

return [

    'name' => 'Inventory',
    'description' => 'Accounting and inventory management under one roof',

    'menu' => [
        'inventory' => 'Inventory',
        'item_groups' => 'Groups',
        'options' => 'Options',
        'manufacturers' => 'Manufacturer',
        'warehouses' => 'Warehouses',
        'histories' => 'Histories',
        'reports' => 'Reports',
    ],

    'inventories' => 'Inventory|Inventories',
    'options' => 'Option|Options',
    'manufacturers' => 'Manufacturer|Manufacturers',
    'warehouses' => 'Warehouse|Warehouses',
    'histories' => 'History|Histories',
    'transactions' => 'Transaction|Transactions',
    'item_groups' => 'Group|Groups',
    'stock_movement' => 'Stock Movement',
    'sku' => 'SKU',
    'quantity' => 'Quantity',

    'information' => 'Information',

    'reports' => [

        'name' => [
            'profit_loss'       => 'Profit & Loss (Inventory)',
            'income_summary'    => 'Income Summary (Inventory)',
            'expense_summary'   => 'Expense Summary (Inventory)',
            'income_expense'    => 'Income vs Expense (Inventory)',
        ],

        'description' => [
            'profit_loss'       => 'Quarterly profit & loss by inventory.',
            'income_summary'    => 'Monthly income summary by inventory.',
            'expense_summary'   => 'Monthly expense summary by inventory.',
            'income_expense'    => 'Monthly income vs expense by inventory.',
        ],
    ],


];
