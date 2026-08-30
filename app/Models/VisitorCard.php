<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCard extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function histories()
    {
        return $this->hasMany(VisitorCardHistory::class);
    }
}
