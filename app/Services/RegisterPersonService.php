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
            'is_employee' => $request['is_employee'] ?? false,
            'expired_at' => null,
            'purpose_of_visit' => $request['purpose_of_visit'] ?? null,
            'status_level' => 1,
            'pic_name' => $request['pic_name'] ?? null,
            'pic_phone' => $request['pic_phone'] ?? null,
            'status' => 'Waiting for approval',
        ]);
    }

    public function getAllRegisteredPerson(array $filters = [])
    {
        $isEmployee = $filters['is_employee'] ?? 0;
        $search     = $filters['search'] ?? null;
        $orderBy    = $filters['order_by'] ?? 'created_at';
        $orderDir   = $filters['order_dir'] ?? 'desc';

        $query = RegisteredPerson::query()
            ->selectRaw("
                registered_persons.*,
                ROW_NUMBER() OVER (
                    PARTITION BY registered_persons.user_id
                    ORDER BY registered_persons.created_at DESC
                ) as rn
            ")
            ->whereHas('user', function ($q) use ($isEmployee, $search) {
                $q->where('is_employee', $isEmployee);

                if (!empty($search)) {
                    $q->where('name', 'like', "%{$search}%");
                }
            });

        return RegisteredPerson::fromSub($query, 'rp')
            ->with('user')
            ->where('rp.rn', 1)
            ->orderBy($orderBy, $orderDir)
            ->get();
    }


    public function getRegisteredPersonById($id){
        return RegisteredPerson::with('user')->where('id', $id)->first();
    }

    public function updateStatusRegisteredPerson(RegisteredPerson $registeredPerson, $data){
        $registeredPerson->expired_at = $data['expired_at'] ?? null;
        $registeredPerson->status = $data['status'];
        $registeredPerson->status_level = $data['status_level'];
        $registeredPerson->save();
    }
    public function updateStatusRegisteredPersonById(int $id, array $data): ?RegisteredPerson
    {
        $registeredPerson = RegisteredPerson::find($id);

        if (!$registeredPerson) {
            return null;
        }

        $fillable = [
            'status',
            'status_level',
            'image_name',
            'area_id',
            'name',
        ];

        $updateData = array_intersect_key(
            $data,
            array_flip($fillable)
        );

        $registeredPerson->fill($updateData);
        $registeredPerson->save();

        return $registeredPerson;
    }

    public function updateAreaId(RegisteredPerson $registeredPerson, $area_id){
        $registeredPerson->area_id = $area_id;
        $registeredPerson->save();
    }

    public function getRegisteredPersonToday($nid)
    {
        return RegisteredPerson::whereHas('user', function ($query) use ($nid) {
                $query->where('nid', $nid);
            })
            ->whereIn('status_level', [1, 2])
            ->orderByDesc('created_at')
            ->first();
    }

}