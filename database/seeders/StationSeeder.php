<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $channelId = '2954806';
        $readApiKey = 'SFWJ5UAULE9O6S0L';
        $results = 10; 

        // Ambil data dari ThingSpeak API
        $response = Http::get("https://api.thingspeak.com/channels/{$channelId}/feeds.json", [
            'api_key' => $readApiKey,
            'results' => $results
        ]);

        $data = $response->json();

        // Kosongkan tabel terlebih dahulu (opsional)
        DB::table('stations')->truncate();

        // Masukkan data ke database
        foreach ($data['feeds'] as $feed) {
            DB::table('stations')->insert([
                'suhu' => $feed['field1'] ?? null, // Sesuaikan field1 dengan field di ThingSpeak
                'kelembaban' => $feed['field2'] ?? null,
                'ph_air' => $feed['field3'] ?? null,
                'gas' => $feed['field4'] ?? null,
                'waktu' => isset($feed['created_at']) ? Carbon::parse($feed['created_at']) : Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}