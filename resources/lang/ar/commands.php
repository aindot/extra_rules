<?php

return [
    'magic' => [
        'description' => 'تخمين فئات التحقق التي ينتمي إليها الإدخال',
        'no_matches' => 'لم يتم العثور على فئات تحقق مطابقة.',
        'matched_single' => 'تم العثور على فئة واحدة مطابقة:',
        'matched_multiple' => 'تم العثور على :count فئات مطابقة:',
        'headers' => [
            'name' => 'الاسم',
            'key' => 'المفتاح',
            'rule_class' => 'فئة القاعدة',
        ],
    ],
    'generate' => [
        'description' => 'إنشاء قيم عينات صالحة لقاعدة تحقق',
        'generated' => 'تم إنشاء :count قيمة/قيم لـ [:rule]:',
    ],
    'redact' => [
        'description' => 'كشف الأسرار وحجبها في النص',
        'no_matches' => 'لم يتم العثور على أسرار.',
        'found' => 'تم العثور على :count سر/أسرار:',
        'one_source' => 'قدم مصدر إدخال واحداً فقط: وسيط أو --file أو --stdin.',
        'unreadable_file' => 'تعذرت قراءة الملف [:file].',
    ],
];
