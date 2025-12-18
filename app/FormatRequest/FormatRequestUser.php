<?php

namespace App\FormatRequest;

use App\Models\User;

class FormatRequestUser
{

    public static function employeeUser($request)
    {
        return [
            'name' => $request['name'],
            'email' => $request['email'] ?? null,
            'nid' => $request['nid'],
            'id_card_number' => self::generateCardNo(),
            'identity_number' => null,
            'phone' => $request['phone'],
            'company' => $request['company'],
            'is_employee' => $request['is_employee'],
            'join_date' => now(),
            'password' => bcrypt('password'),
        ];

    }
    protected static function generateCardNo()
    {
        $date = now()->format('dmy'); // 6 digit, contoh: 251125

        $lastUser = User::latest('id')->first();
        $id = $lastUser ? $lastUser->id + 1 : 1;

        $maxTotalDigit = 10;
        $dateLength = strlen($date);

        $maxIdDigit = $maxTotalDigit - $dateLength;

        // Jika ID lebih panjang dari 4 digit → fallback ke random
        if (strlen((string)$id) > $maxIdDigit) {
            return static::generateRandomCardNo();
        }

        // Gabungkan tanggal + ID sampai total pas 10 digit
        $cardNo = $date . str_pad($id, $maxIdDigit, "0", STR_PAD_LEFT);

        return $cardNo;
    }

    // Fallback: random unique 10 digit
    protected static function generateRandomCardNo()
    {
        do {
            $random = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (User::where('id_card_number', $random)->exists()); // pastikan tidak duplikat

        return $random;
    }

}