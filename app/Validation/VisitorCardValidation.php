<?php

namespace App\Validation;

class VisitorCardValidation
{
    public static function rulesForCreate()
    {
        return [
            'card_number' => 'required|string|max:255|unique:visitor_cards,card_number',
            'status' => 'required|in:0,1',
        ];
    }

    public static function rulesForUpdate($id)
    {
        return [
            'card_number' => 'required|string|max:255|unique:visitor_cards,card_number,' . $id,
            'status' => 'required|in:0,1',
        ];
    }

    public static function messages()
    {
        return [
            'card_number.required' => 'Nomor kartu wajib diisi.',
            'card_number.string' => 'Nomor kartu harus berupa teks.',
            'card_number.max' => 'Nomor kartu maksimal 255 karakter.',
            'card_number.unique' => 'Nomor kartu sudah terdaftar.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus Aktif atau Tidak Aktif.',
        ];
    }
}
