<?php

return [

    'name'              => 'Custom Fields',
    'description'       => 'Add unlimited custom fields to different pages',

    'fields'            => 'Field|Fields',
    'locations'         => 'Location|Locations',
    'sort'              => 'Sort',
    'order'             => 'Position',
    'depend'            => 'Depend',
    'show'              => 'Show',

    'form' => [
        'name'          => 'Name',
        'code'          => 'Code',
        'icon'          => 'FontAwesome Icon',
        'class'         => 'CSS Class',
        'required'      => 'Required',
        'rule'          => 'Validation',
        'before'        => 'Before',
        'after'         => 'After',
        'value'         => 'Value',
        'shows'         => [
            'always'    => 'Always',
            'never'     => 'Never',
            'if_filled' => 'If Filled'
        ]
    ],

    'type' => [
        'select'        => 'Select',
        'radio'         => 'Radio',
        'checkbox'      => 'CheckBox',
        'text'          => 'Text',
        'textarea'      => 'TextArea',
        'date'          => 'Date',
        'time'          => 'Time',
        'date&time'     => 'Date & Time'
    ],

    'item' => [
        'action'   => 'Item Action',
        'name'     => 'Item Name',
        'quantity' => 'Item Quantity',
        'price'    => 'Item Price',
        'taxes'    => 'Item Taxes',
        'total'    => 'Item Total',
    ],
];
