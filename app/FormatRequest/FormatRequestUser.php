<?php

namespace App\FormatRequest;

use App\Models\User;

class FormatRequestUser
{

    public static function employeeUser($request)
    {
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'nid' => $request['nid'],
            'id_card_number' => self::generateCardNo(),
            'identity_number' => null,
            'phone' => $request['phone'],
            'company' => 'PLN Nusantara Power',
            'is_employee' => true,
            'join_date' => now(),
            'password' => bcrypt('password'),
        ];

    }
    protected static function generateCardNo() {

        $lastUser = User::latest('id')->first();
        $number = $lastUser ? $lastUser->id + 1 : 1;

        return sprintf("%08d", $number);
    }

}