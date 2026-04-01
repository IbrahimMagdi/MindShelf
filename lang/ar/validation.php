<?php

return [

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما تكون :other = :value.',
    'active_url' => ':attribute يجب أن يكون رابط صحيح.',
    'after' => ':attribute يجب أن يكون تاريخ بعد :date.',
    'after_or_equal' => ':attribute يجب أن يكون تاريخ بعد أو يساوي :date.',
    'alpha' => ':attribute يجب أن يحتوي على حروف فقط.',
    'alpha_dash' => ':attribute يجب أن يحتوي على حروف وأرقام وشرطات فقط.',
    'alpha_num' => ':attribute يجب أن يحتوي على حروف وأرقام فقط.',
    'array' => ':attribute يجب أن يكون مصفوفة.',
    'ascii' => ':attribute يجب أن يحتوي على أحرف إنجليزية فقط.',
    'before' => ':attribute يجب أن يكون تاريخ قبل :date.',
    'before_or_equal' => ':attribute يجب أن يكون تاريخ قبل أو يساوي :date.',

    'between' => [
        'array' => ':attribute يجب أن يحتوي بين :min و :max عنصر.',
        'file' => ':attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون بين :min و :max.',
        'string' => ':attribute يجب أن يكون بين :min و :max حرف.',
    ],

    'boolean' => ':attribute يجب أن يكون true أو false.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute يجب أن يكون تاريخ صحيح.',
    'date_equals' => ':attribute يجب أن يساوي التاريخ :date.',
    'date_format' => ':attribute يجب أن يطابق الصيغة :format.',
    'different' => ':attribute و :other يجب أن يكونا مختلفين.',
    'digits' => ':attribute يجب أن يكون :digits رقم.',
    'digits_between' => ':attribute يجب أن يكون بين :min و :max رقم.',

    'email' => ':attribute يجب أن يكون بريد إلكتروني صحيح.',
    'exists' => ':attribute المختار غير صحيح.',
    'file' => ':attribute يجب أن يكون ملف.',
    'filled' => ':attribute مطلوب.',

    'gt' => [
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'string' => ':attribute يجب أن يكون أكثر من :value حرف.',
    ],

    'gte' => [
        'numeric' => ':attribute يجب أن يكون أكبر من أو يساوي :value.',
        'string' => ':attribute يجب أن يكون على الأقل :value حرف.',
    ],

    'image' => ':attribute يجب أن يكون صورة.',
    'in' => ':attribute المختار غير صحيح.',
    'integer' => ':attribute يجب أن يكون رقم صحيح.',

    'max' => [
        'numeric' => ':attribute يجب ألا يزيد عن :max.',
        'string' => ':attribute يجب ألا يزيد عن :max حرف.',
    ],

    'min' => [
        'numeric' => ':attribute يجب ألا يقل عن :min.',
        'string' => ':attribute يجب ألا يقل عن :min حرف.',
    ],

    'not_in' => ':attribute المختار غير صحيح.',
    'numeric' => ':attribute يجب أن يكون رقم.',
    'regex' => 'صيغة :attribute غير صحيحة.',

    'required' => ':attribute مطلوب.',
    'required_if' => ':attribute مطلوب عندما تكون :other = :value.',
    'required_with' => ':attribute مطلوب عند وجود :values.',

    'same' => ':attribute يجب أن يطابق :other.',

    'size' => [
        'numeric' => ':attribute يجب أن يكون :size.',
        'string' => ':attribute يجب أن يكون :size حرف.',
    ],

    'string' => ':attribute يجب أن يكون نص.',
    'unique' => ':attribute مستخدم من قبل.',
    'url' => ':attribute يجب أن يكون رابط صحيح.',
    'custom' => [
        'birthdate' => [
            'before' => 'يجب ألا يقل العمر عن 12 سنة.',
        ],
    ],
    'attributes' => [
        'name'      => 'الاسم',
        'email'     => 'البريد الإلكتروني',
        'password'  => 'كلمة المرور',
        'birthdate' => 'تاريخ الميلاد',
        'gender'    => 'النوع',
        'role'      => 'نوع الحساب',
    ],

];
