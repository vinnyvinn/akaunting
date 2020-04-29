<?php

return [

    'name'              => 'حقول مخصصة',
    'description'       => 'إضافة حقول مخصصة غير محدودة إلى صفحات مختلفة',

    'fields'            => 'حقل|حقول',
    'locations'         => 'مكان|أماكن',
    'sort'              => 'ترتيب',
    'order'             => 'الموضع',
    'depend'            => 'تعتمد',
    'show'              => 'عرض',

    'form' => [
        'name'          => 'الاسم',
        'code'          => 'الكود',
        'icon'          => 'ايقونة FontAwesome',
        'class'         => 'CSS Class',
        'required'      => 'مطلوب',
        'rule'          => 'التحقق',
        'before'        => 'قبل',
        'after'         => 'بعد',
        'value'         => 'القيمة',
        'shows'         => [
            'always'    => 'دائماً',
            'never'     => 'أبداً',
            'if_filled' => 'لو تم تعبئته'
        ]
    ],

    'type' => [
        'select'        => 'قائمة اختيار',
        'radio'         => 'زر اختيار',
        'checkbox'      => 'زر اختيار متعدد',
        'text'          => 'نص',
        'textarea'      => 'صندوق نصي كبير',
        'date'          => 'تاريخ',
        'time'          => 'وقت',
        'date&time'     => 'الوقت والتاريخ'
    ],

    'item' => [
        'action'   => 'إجراءات العنصر',
        'name'     => 'اسم العنصر',
        'quantity' => 'كمية العنصر',
        'price'    => 'سعر العنصر',
        'taxes'    => 'ضرائب العنصر',
        'total'    => 'الاجمالي',
    ],
];
