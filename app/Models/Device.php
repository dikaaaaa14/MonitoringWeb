<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'is_online', 'ip_address'];

    public function sensors()
    {
        return $this->hasMany(SensorData::class);
    }
}