<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Station extends Model
{
    protected $fillable = ['suhu', 
        'kelembaban', 
        'ph_air', 
        'gas', 
        'waktu'];
}
