<?php

namespace App\Services;

use App\Models\User;
use App\Models\RegisteredPerson;

class RegisterPersonService{

   
    public function createRegisteredPerson($request){ 
        return RegisteredPerson::create([
            'user_id' => $request['user_id'],
            'name' => $request['name'],
            'id_card_num' => null,
            'id_number' => null,
            'face_permission' => null,
            'id_card_permission' => null,
            'face_card_permission' => null,
            'id_permission' => null,
            'tag' => null,
            'phone' =>null,
            'password_fr' => null,
            'password_permission' => null,
            'person_image' => $request['person_image'],
            'is_employee' => true,
            'expired_at' => null,
            'purpose_of_visit' => $request['purpose_of_visit'] ?? null,
        ]);
    }
}