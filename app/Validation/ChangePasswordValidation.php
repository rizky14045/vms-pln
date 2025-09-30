<?php

namespace App\Validation;

class ChangePasswordValidation
{
    public static function rulesForChange()
    {
        return [
            'old_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
            ],
            'confirm_password' => 'string|required_with:new_password|same:new_password'
        ];
    }


    public static function messages()
    {
        return [
            'old_password.required' => 'Password lama harus di isi!',
            'old_password.string' => 'Password lama harus di isi!',
            'new_password.required' => 'Password Baru harus di isi!',
            'new_password.string' => 'Password Baru harus di isi!',
            'confirm_password.required_new' => 'Password Baru harus di isi!',
            'confirm_password.string' => 'Password Baru harus di isi!',
            'confirm_password.same' => 'Kata sandi tidak cocok!',
        ];
    }
}
