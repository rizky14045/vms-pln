<?php

namespace App\Validation;


class AuthValidation
{
    public static function rulesForLogin()
    {
        return [
            'email' => 'required|string|max:255|email',
            'password' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.string'   => 'Email harus berupa teks.',
            'email.max'      => 'Email tidak boleh lebih dari 255 karakter.',
            'email.email'    => 'Format email tidak valid.',

            'password.required' => 'Password wajib diisi.',
        ];
    }
}
