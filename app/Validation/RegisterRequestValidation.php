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
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|regex:/^628\d{7,12}$/',
        ];
    }

    public static function rulesForVisitor()
    {
        return [
            'nid' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'person_image' => 'required|file|mimes:png,jpeg,jpg',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|regex:/^628\d{7,12}$/',
            'company' => 'required|string|max:255',
            'purpose_of_visit' => 'required|string|max:500',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|regex:/^628\d{7,12}$/',
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
            'person_image.mimes' => 'Format foto tidak sesuai. Hanya PNG, JPEG, dan JPG yang diizinkan.',
            'person_image.dimensions' => 'Resolusi foto tidak boleh lebih dari 1920x1080 piksel.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak sesuai.',

            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak sesuai. Contoh: 628XXXXXXXX.',

            'company.required' => 'Perusahaan wajib diisi.',
            'company.string' => 'Perusahaan harus berupa teks.',
            'company.max' => 'Perusahaan maksimal 255 karakter.',
            'purpose_of_visit.required' => 'Tujuan kunjungan wajib diisi.',
            'purpose_of_visit.string' => 'Tujuan kunjungan harus berupa teks.',
            'purpose_of_visit.max' => 'Tujuan kunjungan maksimal 500 karakter.',
            'pic_name.required' => 'Nama PIC wajib diisi.',
            'pic_name.string' => 'Nama PIC harus berupa teks.',
            'pic_name.max' => 'Nama PIC maksimal 255 karakter.',
            'pic_phone.required' => 'Nomor telepon PIC wajib diisi.',
            'pic_phone.regex' => 'Format nomor telepon PIC tidak sesuai. Contoh:628XXXXXXXX.',
        ];
    }

}
