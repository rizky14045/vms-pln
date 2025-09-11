<?php

namespace App\Http\Validation;

use App\Rules\UniqueAccountCode;

class AreaValidation
{
    public static function rulesForCreate()
    {
        return [
            'description' => 'required|string|max:255',
            'device_id' => 'required|array|min:1',
            'device_id.*' => 'required|string',
        ];
    }
    public static function rulesForUpdate()
    {
        return [
            'description' => 'required|string|max:255',
            'device_id' => 'required|array|min:1',
            'device_id.*' => 'required|string',
        ];
    }

    public static function messages()
    {
        return [
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string'   => 'Deskripsi harus berupa teks.',
            'description.max'      => 'Deskripsi tidak boleh lebih dari 255 karakter.',

            'device_id.required'   => 'Device wajib dipilih.',
            'device_id.array'      => 'Device harus berupa array.',
            'device_id.min'        => 'Minimal 1 device harus dipilih.',

            'device_id.*.required' => 'Setiap device wajib diisi.',
            'device_id.*.string'   => 'Setiap device harus berupa teks.',
        ];
    }
}
