<?php

namespace App\Validation;

class DeviceValidation
{
    public static function rulesForCreate()
    {
        return [
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'area_id' => 'required|array|min:1',
            'area_id.*' => 'required|string',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'area_id' => 'required|array|min:1',
            'area_id.*' => 'required|string',
        ];
    }

    public static function messages()
    {
        return [
            'device_name.required' => 'Nama device wajib diisi.',
            'device_name.string' => 'Nama device harus berupa teks.',
            'device_name.max' => 'Nama device maksimal 255 karakter.',

            'device_type.required' => 'Tipe device wajib diisi.',
            'device_type.string' => 'Tipe device harus berupa teks.',
            'device_type.max' => 'Tipe device maksimal 255 karakter.',

            'area_id.required' => 'Area wajib diisi.',
            'area_id.array' => 'Area harus berupa array.',
            'area_id.min' => 'Area minimal 1 item.',
            'area_id.*.required' => 'Setiap item area wajib diisi.',
            'area_id.*.string' => 'Setiap item area harus berupa teks.',
        ];
    }
}
