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
            'email' => $request['nid'].'@email.com',
            'nid' => $request['nid'],
            'password' => bcrypt('password'),
        ]);
    }
}