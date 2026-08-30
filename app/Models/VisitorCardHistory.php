<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCardHistory extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function visitorCard()
    {
        return $this->belongsTo(VisitorCard::class);
    }

    public function registeredPerson()
    {
        return $this->belongsTo(RegisteredPerson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
