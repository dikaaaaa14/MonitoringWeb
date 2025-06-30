<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorsData;

class SensorDataController extends Controller
{
    public function index()
    {
        // Ambil 50 data terbaru
        $sensorData = SensorsData::orderBy('created_at', 'desc')
                               ->take(50)
                               ->get();
        
        // Format data untuk chart
        $timestamps = $sensorData->pluck('created_at')->map(function($date) {
            return $date->format('H:i:s');
        })->toArray();
        
        $temperatureData = $sensorData->pluck('temperature')->toArray();
        $humidityData = $sensorData->pluck('humidity')->toArray();
        $phData = $sensorData->pluck('ph')->toArray();
        $gasData = $sensorData->pluck('gas')->toArray();
        
        // Hitung rata-rata
        $avgTemperature = $sensorData->avg('temperature');
        $avgHumidity = $sensorData->avg('humidity');
        $avgPh = $sensorData->avg('ph');
        $avgGas = $sensorData->avg('gas');
        
        // Format data untuk tabel
        $tableData = $sensorData->map(function($item) {
            return [
                'timestamp' => $item->created_at->format('Y-m-d H:i:s'),
                'temperature' => number_format($item->temperature, 1),
                'humidity' => number_format($item->humidity, 0),
                'ph' => number_format($item->ph, 1),
                'gas' => number_format($item->gas, 0)
            ];
        });
        
        return response()->json([
            'data' => [
                'timestamps' => $timestamps,
                'temperature' => $avgTemperature,
                'humidity' => $avgHumidity,
                'ph' => $avgPh,
                'gas' => $avgGas,
                'temperatureData' => $temperatureData,
                'humidityData' => $humidityData,
                'phData' => $phData,
                'gasData' => $gasData,
                'allData' => $tableData
            ]
        ]);
    }
}