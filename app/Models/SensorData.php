<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = ['station_id', 'temperature', 'humidity', 'ph', 'gas', 'recorded_at'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
