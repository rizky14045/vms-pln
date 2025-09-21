<?php

namespace App\Validation;

class AreaValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    // public static function rulesForCreate()
    // {
    //     return [
    //         'description' => 'required|string|max:255',
    //         'device_id' => 'required|array|min:1',
    //         'device_id.*' => 'required|string',
    //     ];
    // }
    public static function rulesForUpdate()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama area wajib diisi.',
            'name.string' => 'Nama area harus berupa teks.',
            'name.max' => 'Nama area maksimal 255 karakter.',
            
            'description.required' => 'Deskripsi area wajib diisi.',
            'description.string' => 'Deskripsi area harus berupa teks.',
            'description.max' => 'Deskripsi area maksimal 255 karakter.',
        ];
    }
}
