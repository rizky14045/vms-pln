<?php

namespace App\Validation;

class ProductValidation
{
    public static function rulesForCreate()
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:1000',
            'price'             => 'required|numeric|min:0',
            'product_type_id'   => 'required_without:new_product_type|nullable|exists:product_types,id',
            'new_product_type'  => 'required_without:product_type_id|nullable|string|max:255|unique:product_types,name',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:1000',
            'price'             => 'required|numeric|min:0',
            'product_type_id'   => 'required_without:new_product_type|nullable|exists:product_types,id',
            'new_product_type'  => 'required_without:product_type_id|nullable|string|max:255|unique:product_types,name',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.string'   => 'Nama produk harus berupa teks.',
            'name.max'      => 'Nama produk maksimal 255 karakter.',

            'description.string' => 'Deskripsi produk harus berupa teks.',
            'description.max'    => 'Deskripsi produk maksimal 1000 karakter.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric'  => 'Harga produk harus berupa angka.',
            'price.min'      => 'Harga produk tidak boleh kurang dari 0.',

            'product_type_id.required_without' => 'Pilih tipe produk atau tambahkan tipe baru.',
            'product_type_id.exists'           => 'Tipe produk tidak valid.',

            'new_product_type.required_without' => 'Pilih tipe produk atau tambahkan tipe baru.',
            'new_product_type.string'           => 'Tipe produk baru harus berupa teks.',
            'new_product_type.max'              => 'Tipe produk baru maksimal 255 karakter.',
            'new_product_type.unique'           => 'Tipe produk tersebut sudah ada, silakan pilih dari daftar.',
        ];
    }
}
