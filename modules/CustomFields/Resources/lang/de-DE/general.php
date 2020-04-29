<?php

return [

    'name'              => 'Benutzerdefinierte Felder',
    'description'       => 'Fügen Sie unbegrenzte benutzerdefinierte Felder zu verschiedenen Modulen hinzu',

    'fields'            => 'Felder|Felder',
    'locations'         => 'Position|Positionen',
    'sort'              => 'Sortierung',
    'order'             => 'Position',
    'depend'            => 'Abhängigkeit',
    'show'              => 'Anzeigen',

    'form' => [
        'name'          => 'Name',
        'code'          => 'Code',
        'icon'          => 'FontAwesome Icon',
        'class'         => 'CSS-Klasse',
        'required'      => 'Pflichtfeld',
        'rule'          => 'Validierung',
        'before'        => 'Vorher',
        'after'         => 'Danach',
        'value'         => 'Wert',
        'shows'         => [
            'always'    => 'Immer',
            'never'     => 'Nie',
            'if_filled' => 'Wenn ausgefüllt'
        ]
    ],

    'type' => [
        'select'        => 'Auswahl',
        'radio'         => 'Radio',
        'checkbox'      => 'Checkbox',
        'text'          => 'Text',
        'textarea'      => 'Textfeld',
        'date'          => 'Datum',
        'time'          => 'Zeit',
        'date&time'     => 'Datum und Zeit'
    ],

    'item' => [
        'action'   => 'Artikelaktion',
        'name'     => 'Elementnamen',
        'quantity' => 'Artikelmenge',
        'price'    => 'Artikelpreis',
        'taxes'    => 'Artikelsteuern',
        'total'    => 'Artikel gesamt',
    ],
];
