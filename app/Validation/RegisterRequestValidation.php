<?php

namespace App\Validation;

class RegisterRequestValidation
{
    public static function rulesForCreate()
    {
        return [
            'nid' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'person_image' => 'required|file|mimes:png,jpeg,jpg', 
        ];
    }


    public static function messages()
    {
        return [
            'nid.required' => 'NID wajib diisi.',
            'nid.string' => 'NID harus berupa teks.',
            'nid.max' => 'NID maksimal 255 karakter.',

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'person_image.required' => 'Foto wajib diisi.',
            'person_image.file' => 'Foto harus berupa file.',
            'person_image.mimes' => 'Format foto tidak sesuai.',
        ];
    }
}
