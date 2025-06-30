<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Station;
use App\Models\SensorData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
{
    public function list()
    {
        $channelId = '2954806';
        $apiKey = 'SFWJ5UAULE9O6S0L';

        $url = "https://api.thingspeak.com/channels/{$channelId}/feeds.json?api_key={$apiKey}&results=200";

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Debug logging
                Log::info('ThingSpeak Response:', $data);
                
                // Pastikan ada data feeds
                if (isset($data['feeds']) && is_array($data['feeds']) && !empty($data['feeds'])) {
                    $feeds = $data['feeds'];
                    
                    // Filter data yang valid saja
                    $validFeeds = array_filter($feeds, function($feed) {
                        return isset($feed['created_at']) && 
                               ($feed['field1'] !== null || $feed['field2'] !== null || 
                                $feed['field3'] !== null || $feed['field4'] !== null);
                    });
                    
                    Log::info('Valid feeds count: ' . count($validFeeds));
                    
                    return view('station.list', compact('validFeeds'))->with('feeds', $validFeeds);
                } else {
                    Log::warning('No feeds data in ThingSpeak response');
                    return view('station.list')->with('feeds', [])->with('error', 'Tidak ada data sensor ditemukan');
                }
            } else {
                Log::error('ThingSpeak API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return view('station.list')->with('feeds', [])->with('error', 'Gagal mengambil data dari ThingSpeak. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception in list method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return view('station.list')->with('feeds', [])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk testing koneksi ThingSpeak
    public function testThingSpeak()
    {
        $channelId = '2954806';
        $apiKey = 'SFWJ5UAULE9O6S0L';
        
        // Test dengan berbagai parameter
        $tests = [
            'default' => "https://api.thingspeak.com/channels/{$channelId}/feeds.json?api_key={$apiKey}",
            'with_results' => "https://api.thingspeak.com/channels/{$channelId}/feeds.json?api_key={$apiKey}&results=10",
            'last_entry' => "https://api.thingspeak.com/channels/{$channelId}/feeds/last.json?api_key={$apiKey}",
            'channel_info' => "https://api.thingspeak.com/channels/{$channelId}.json?api_key={$apiKey}"
        ];
        
        $results = [];
        
        foreach ($tests as $testName => $url) {
            try {
                $response = Http::timeout(30)->get($url);
                $results[$testName] = [
                    'status' => $response->status(),
                    'success' => $response->successful(),
                    'data' => $response->json(),
                    'body_length' => strlen($response->body())
                ];
            } catch (\Exception $e) {
                $results[$testName] = [
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return response()->json($results);
    }

    // Method untuk debug data
    public function debugData()
    {
        $channelId = '2954806';
        $apiKey = 'SFWJ5UAULE9O6S0L';
        
        $url = "https://api.thingspeak.com/channels/{$channelId}/feeds.json?api_key={$apiKey}&results=5";
        
        try {
            $response = Http::timeout(30)->get($url);
            
            return response()->json([
                'url' => $url,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'raw_body' => $response->body(),
                'json_data' => $response->json(),
                'successful' => $response->successful()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function getThingSpeakData($channelId, $apiKey, $options = [])
    {
        $defaultOptions = [
            'results' => 100, 
            'api_key' => $apiKey
        ];
        
        $params = array_merge($defaultOptions, $options);
        
        $response = Http::get("https://api.thingspeak.com/channels/{$channelId}/feeds.json", $params);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        return ['error' => 'Failed to fetch data from ThingSpeak', 'status' => $response->status()];
    }

    /**
     * Mengambil data sensor spesifik dari ThingSpeak dan menyimpannya ke database lokal
     * 
     * @param int $stationId ID stasiun
     * @param array $options Parameter tambahan untuk request ThingSpeak
     * @return array Data yang diambil
     */
    public function fetchAndStoreThingSpeakData($stationId, $options = [])
    {
        $station = Station::findOrFail($stationId);
        
        // Ambil data dari ThingSpeak
        $thingSpeakData = $this->getThingSpeakData($station->channel_id, $station->api_key, $options);
        
        if (isset($thingSpeakData['feeds']) && !empty($thingSpeakData['feeds'])) {
            $feeds = $thingSpeakData['feeds'];
            $savedCount = 0;
            
            foreach ($feeds as $feed) {
                // Periksa apakah data sudah ada berdasarkan timestamp
                $timestamp = \Carbon\Carbon::parse($feed['created_at']);
                
                $exists = SensorData::where('station_id', $stationId)
                    ->where('created_at', $timestamp)
                    ->exists();
                    
                if (!$exists) {
                    // Simpan data baru ke database
                    SensorData::create([
                        'station_id' => $stationId,
                        'created_at' => $timestamp,
                        'temperature' => $feed['field1'] ?? null,
                        'humidity' => $feed['field2'] ?? null,
                        'ph' => $feed['field3'] ?? null,
                        'gas' => $feed['field4'] ?? null
                    ]);
                    
                    $savedCount++;
                }
            }
            
            return [
                'success' => true,
                'message' => "Successfully fetched data from ThingSpeak. Saved {$savedCount} new records.",
                'data' => $feeds
            ];
        }
        
        return [
            'success' => false,
            'message' => 'No data or error in ThingSpeak response',
            'response' => $thingSpeakData
        ];
    }

    /**
     * Route endpoint untuk sinkronisasi manual data ThingSpeak
     * 
     * @param Request $request
     * @param int $stationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncThingSpeak(Request $request, $stationId)
    {
        $options = [];
        
        // Tambahkan parameter jika ada
        if ($request->has('days')) {
            $options['days'] = $request->days;
        }
        
        if ($request->has('start') && $request->has('end')) {
            $options['start'] = $request->start;
            $options['end'] = $request->end;
        }
        
        if ($request->has('results')) {
            $options['results'] = $request->results;
        }
        
        $result = $this->fetchAndStoreThingSpeakData($stationId, $options);
        
        if ($request->wantsJson()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * API endpoint untuk mendapatkan data sensor terbaru
     * 
     * @param int $stationId
     * @param string|null $sensorType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestSensorData($stationId, $sensorType = null)
    {
        $station = Station::findOrFail($stationId);
        
        // Coba ambil dari database lokal dulu
        $query = SensorData::where('station_id', $stationId)
            ->orderBy('created_at', 'desc')
            ->limit(1);
        
        $localData = $query->first();
        
        // Jika tidak ada data lokal atau data sudah lama (> 5 menit), ambil dari ThingSpeak
        $shouldFetchFromThingSpeak = !$localData || 
            now()->diffInMinutes($localData->created_at) > 5;
        
        if ($shouldFetchFromThingSpeak) {
    $response = Http::get("https://api.thingspeak.com/channels/{$station->channel_id}/feeds/last.json", [
        'api_key' => $station->api_key
    ]);

    if ($response->successful()) {
        $data = $response->json();

        if ($data && isset($data['created_at'])) {
            SensorData::create([
                'station_id' => $stationId,
                'created_at' => \Carbon\Carbon::parse($data['created_at']),
                'temperature' => $data['field1'] ?? null,
                'humidity' => $data['field2'] ?? null,
                'ph' => $data['field3'] ?? null,
                'gas' => $data['field4'] ?? null
            ]);

            $result = [
                'station_id' => $stationId,
                'station_name' => $station->name,
                'timestamp' => $data['created_at'],
                'temperature' => $data['field1'] ?? null,
                'humidity' => $data['field2'] ?? null,
                'ph' => $data['field3'] ?? null,
                'gas' => $data['field4'] ?? null
            ];
        } else {
            // Respon kosong / tidak sesuai
            Log::warning('ThingSpeak response missing created_at or empty', [
                'response' => $data
            ]);

            if ($localData) {
                $result = [
                    'station_id' => $stationId,
                    'station_name' => $station->name,
                    'timestamp' => $localData->created_at,
                    'temperature' => $localData->temperature,
                    'humidity' => $localData->humidity,
                    'ph' => $localData->ph,
                    'gas' => $localData->gas
                ];
            } else {
                return response()->json([
                    'error' => 'No valid data from ThingSpeak or local database'
                ], 404);
            }
        }
    } else {
        // Gagal ambil dari ThingSpeak
        if ($localData) {
            $result = [
                'station_id' => $stationId,
                'station_name' => $station->name,
                'timestamp' => $localData->created_at,
                'temperature' => $localData->temperature,
                'humidity' => $localData->humidity,
                'ph' => $localData->ph,
                'gas' => $localData->gas
            ];
        } else {
            return response()->json([
                'error' => 'No data available from ThingSpeak or local database'
            ], 404);
        }
    }
}

        
        // Filter berdasarkan tipe sensor jika ditentukan
        if ($sensorType && isset($result[$sensorType])) {
            return response()->json([
                'station_id' => $result['station_id'],
                'station_name' => $result['station_name'],
                'timestamp' => $result['timestamp'],
                $sensorType => $result[$sensorType]
            ]);
        }
        
        return response()->json($result);
    }

    /**
     *
     * 
     * @param Request $request
     * @param int $stationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSensorHistory(Request $request, $station_id)
{
    $start_date = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
    $end_date = $request->input('end_date', now()->format('Y-m-d'));
    $interval = $request->input('interval', '6min');
    $limit = $request->input('limit', 100);

    if ($interval === '6min') {
        $groupBy = DB::raw("
            CONCAT(
                DATE_FORMAT(created_at, '%Y-%m-%d %H:'),
                LPAD(FLOOR(MINUTE(created_at) / 6) * 6, 2, '0'),
                ':00'
            )
        ");
    } else {
        // interval lain...
    }

    $data = Station::select(
        $groupBy . ' AS interval_time',
        DB::raw('AVG(temperature) as temperature'),
        DB::raw('AVG(humidity) as humidity'),
        DB::raw('AVG(ph) as ph'),
        DB::raw('AVG(gas) as gas')
    )
    ->where('station_id', $station_id)
    ->whereBetween('created_at', [$start_date, $end_date])
    ->groupBy('interval_time')
    ->orderBy('interval_time', 'asc')
    ->limit($limit)
    ->get();

    return response()->json([
        'station_id' => $station_id,
        'interval' => $interval,
        'data' => $data
    ]);
}


}