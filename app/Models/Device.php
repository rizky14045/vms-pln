<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'device_areas', 'device_id', 'area_id')
            ->withTimestamps();
    }
}
