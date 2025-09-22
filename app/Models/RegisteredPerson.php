<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'id_card_num',
        'id_number',
        'face_permission',
        'id_card_permission',
        'face_card_permission',
        'id_permission',
        'tag',
        'phone',
        'password_fr',
        'password_permission',
        'person_image',
        'is_employee',
        'expired_at',
        'purpose_of_visit',
    ];

    protected $table = 'registered_persons';
}
