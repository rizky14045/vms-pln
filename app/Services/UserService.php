<?php

namespace App\Services;

use App\Models\User;

class UserService{

    public function getUserById($id){
        return User::where('id',$id)->first();
    }

    public function getUserByNid($nid){
        return User::where('nid',$nid)->first();
    }

    public function getUserByEmail($email) {
        return User::where('email',$email)->first();
    }

    public function createUser($request){

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'nid' => $request['nid'] ?? '',
            'id_card_number' => $request['id_card_number'] ?? '',
            'identity_number' => $request['identity_number'] ?? '',
            'phone' => $request['phone'] ?? '',
            'company' => $request['company'] ?? '',
            'is_employee' => $request['is_employee'],
            'join_date' => now(),
            'password' => bcrypt('password'),
        ]);
    }

    public function updateStatusRegistered(User $user){
        $user->is_registered = true;
        $user->save();
    }
}