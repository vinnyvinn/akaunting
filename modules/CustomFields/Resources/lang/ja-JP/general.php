<?php

return [

    'name'              => 'カスタムフィールド',
    'description'       => '別のページに無制限のカスタムフィールドを追加する',

    'fields'            => 'フィールド|フィールド',
    'locations'         => '場所|場所',
    'sort'              => '並べ替え',
    'order'             => 'ポジション',
    'depend'            => '依存',
    'show'              => '見せる',

    'form' => [
        'name'          => '名前',
        'code'          => 'コード',
        'icon'          => 'FontAwesomeアイコン',
        'class'         => 'CSSクラス',
        'required'      => '必須',
        'rule'          => '検証',
        'before'        => '前',
        'after'         => '後',
        'value'         => 'バリュー',
        'shows'         => [
            'always'    => '常に',
            'never'     => '決して',
            'if_filled' => 'もしいっぱい'
        ]
    ],

    'type' => [
        'select'        => 'セレクト',
        'radio'         => 'ラジオ',
        'checkbox'      => 'チェックボックス',
        'text'          => 'テキスト',
        'textarea'      => 'テキスト領域',
        'date'          => '日付',
        'time'          => '時間',
        'date&time'     => '日付時刻'
    ],

    'item' => [
        'action'   => '項目アクション',
        'name'     => '項目名',
        'quantity' => '品目数量',
        'price'    => '品目価格',
        'taxes'    => 'アイテム税',
        'total'    => '品目合計',
    ],
];
