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
            'person_image' => $request['image_name'],
            'is_employee' => true,
            'expired_at' => null,
            'purpose_of_visit' => $request['purpose_of_visit'] ?? null,
            'status_level' => 1,
            'status' => 'Waiting for approval',
        ]);
    }

    public function getAllRegisteredPerson(){
        return RegisteredPerson::with('user')->latest()->get();
    }

    public function getRegisteredPersonById($id){
        return RegisteredPerson::with('user')->where('id', $id)->first();
    }

    public function updateStatusRegisteredPerson(RegisteredPerson $registeredPerson, $data){
        $registeredPerson->status = $data['status'];
        $registeredPerson->status_level = $data['status_level'];
        $registeredPerson->save();
    }
    public function updateAreaId(RegisteredPerson $registeredPerson, $area_id){
        $registeredPerson->area_id = $area_id;
        $registeredPerson->save();
    }

    public function getRegisteredPersonToday($nid){
        $today = now()->format('Y-m-d');
        return RegisteredPerson::whereHas('user', function($query) use ($nid) {
            $query->where('nid', $nid);
        })->whereDate('created_at', $today)->first();
    }
}