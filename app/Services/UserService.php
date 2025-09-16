<?php

namespace App\Services;

use App\Models\User;

class UserService{

    public function getUserById($id){
        return User::where('id',$id)->first();
    }
    public function getUserByEmail($email) {
        return User::where('email',$email)->first();
    }
}