<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'tr_date', 'tr_time', 'card_no', 'transaction', 'tr_code',
        'door_name', 'card_name', 'department', 'staff_no', 'nric'
    ];
}
