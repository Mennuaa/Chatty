<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailOrPhoneRequired implements Rule
{
    public function passes($attribute, $value)
    {
        $data = request()->all();
        return !empty($data['email']) || !empty($data['phone']);
    }

    public function message()
    {
        return 'Телефон либо электронная почта должна быть заполнена';
    }
}
