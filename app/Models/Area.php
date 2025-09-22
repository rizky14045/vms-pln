<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function childrenArea()
    {
        return $this->hasMany(Area::class, 'parent_id')->with('childrenArea');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'device_areas', 'area_id', 'device_id')
            ->withTimestamps();
    }
}
