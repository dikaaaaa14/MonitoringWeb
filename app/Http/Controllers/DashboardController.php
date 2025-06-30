<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected $thingspeakConfig;
    
    public function __construct()
    {
        // Default ThingSpeak configuration
        $this->thingspeakConfig = [
            'channelId' => '2954806',
            'readApiKey' => 'SFWJ5UAULE9O6S0L',
            'tempField' => 1,
            'humidityField' => 2,
            'phField' => 3,
            'gasField' => 4,
            'resultsCount' => 40,
        ];
        
        // Apply middleware for authenticated users only
        $this->middleware('auth');
    }
    
    /**
     * Display the dashboard
     */
    





  

    public function index()
    {
        return view('dashboard', ['title' => 'Dashboard']);
    }

    
    /**
     * Fetch data from ThingSpeak API
     */
    public function fetchThingSpeakData(Request $request)
    {
        // Try to get data from cache first (5 minutes TTL)
        $cacheKey = 'thingspeak_data_' . $this->thingspeakConfig['channelId'];
        
        if (Cache::has($cacheKey) && !$request->has('refresh')) {
            return response()->json(Cache::get($cacheKey));
        }
        
        try {
            // Construct the API URL
            $apiUrl = "https://api.thingspeak.com/channels/{$this->thingspeakConfig['channelId']}/feeds.json";
            
            // Add parameters
            $params = [
                'results' => $this->thingspeakConfig['resultsCount'],
                'api_key' => $this->thingspeakConfig['readApiKey'],
            ];
            
            // Make the request
            $response = Http::get($apiUrl, $params);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Process the data
                $processedData = $this->processThingSpeakData($data);
                
                // Cache the data for 5 minutes
                Cache::put($cacheKey, $processedData, 300);
                
                return response()->json($processedData);
            } else {
                return response()->json(['error' => 'Failed to fetch data from ThingSpeak'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Process the ThingSpeak data
     */
    protected function processThingSpeakData($data)
    {
        if (!isset($data['feeds']) || empty($data['feeds'])) {
            return [
                'status' => 'error',
                'message' => 'No data available',
                'connected' => false,
                'summary' => [
                    'totalData' => 0,
                    'avgTemp' => 0,
                    'avgHumidity' => 0,
                    'deviceStatus' => 0
                ],
                'charts' => [
                    'labels' => [],
                    'temperature' => [],
                    'humidity' => [],
                    'ph' => [],
                    'gas' => []
                ]
            ];
        }
        
        // Prepare data for charts
        $labels = [];
        $tempData = [];
        $humidityData = [];
        $phData = [];
        $gasData = [];
        
        // Process each data point
        foreach ($data['feeds'] as $feed) {
            // Format the date for display
            $dateObj = Carbon::parse($feed['created_at']);
            $timeLabel = $dateObj->format('H:i:s');
            $labels[] = $timeLabel;
            
            // Extract field values
            $tempField = 'field' . $this->thingspeakConfig['tempField'];
            $humidityField = 'field' . $this->thingspeakConfig['humidityField'];
            $phField = 'field' . $this->thingspeakConfig['phField'];
            $gasField = 'field' . $this->thingspeakConfig['gasField'];
            
            $temp = isset($feed[$tempField]) ? (float) $feed[$tempField] : null;
            $humidity = isset($feed[$humidityField]) ? (float) $feed[$humidityField] : null;
            $ph = isset($feed[$phField]) ? (float) $feed[$phField] : null;
            $gas = isset($feed[$gasField]) ? (float) $feed[$gasField] : null;
            
            // Add to data arrays
            $tempData[] = $temp;
            $humidityData[] = $humidity;
            $phData[] = $ph;
            $gasData[] = $gas;
        }
        
        // Calculate averages
        $avgTemp = $this->calculateAverage($tempData);
        $avgHumidity = $this->calculateAverage($humidityData);
        
        return [
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'connected' => true,
            'lastUpdate' => Carbon::now()->toDateTimeString(),
            'summary' => [
                'totalData' => count($data['feeds']),
                'avgTemp' => round($avgTemp, 1),
                'avgHumidity' => round($avgHumidity, 0),
                'deviceStatus' => 1
            ],
            'charts' => [
                'labels' => $labels,
                'temperature' => $tempData,
                'humidity' => $humidityData,
                'ph' => $phData,
                'gas' => $gasData
            ],
            'rawData' => $data['feeds']
        ];
    }
    
    /**
     * Calculate average from array
     */
    protected function calculateAverage($array)
    {
        $validValues = array_filter($array, function ($value) {
            return $value !== null && is_numeric($value);
        });
        
        if (empty($validValues)) {
            return 0;
        }
        
        return array_sum($validValues) / count($validValues);
    }
    
    /**
     * Generate and download report
     */
    public function generateReport(Request $request)
    {
        try {
            // Fetch data from ThingSpeak
            $apiUrl = "https://api.thingspeak.com/channels/{$this->thingspeakConfig['channelId']}/feeds.json";
            
            $params = [
                'results' => $request->input('results', 100), // Get more data for reports
                'api_key' => $this->thingspeakConfig['readApiKey'],
            ];
            
            $response = Http::get($apiUrl, $params);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (!isset($data['feeds']) || empty($data['feeds'])) {
                    return response()->json(['error' => 'No data available for report'], 404);
                }
                
                // Create CSV content
                $csvContent = $this->generateCSVContent($data['feeds']);
                
                $filename = 'laporan-sensor-' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
                
                return response($csvContent)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', "attachment; filename=\"$filename\"");
            } else {
                return response()->json(['error' => 'Failed to fetch data from ThingSpeak'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Generate CSV content
     */
    protected function generateCSVContent($feeds)
    {
        // Headers
        $headers = ['timestamp', 'temperature', 'humidity', 'ph', 'gas'];
        $csvContent = implode(',', $headers) . "\n";
        
        foreach ($feeds as $feed) {
            $row = [
                $feed['created_at'],
                $feed['field' . $this->thingspeakConfig['tempField']] ?? 'N/A',
                $feed['field' . $this->thingspeakConfig['humidityField']] ?? 'N/A',
                $feed['field' . $this->thingspeakConfig['phField']] ?? 'N/A',
                $feed['field' . $this->thingspeakConfig['gasField']] ?? 'N/A'
            ];
            
            $csvContent .= implode(',', $row) . "\n";
        }
        
        return $csvContent;
    }
    
    /**
     * Update ThingSpeak settings
     */
    public function updateSettings(Request $request)
    
    {
        // Validate the incoming request
        $validated = $request->validate([
            'channelId' => 'required|string',
            'readApiKey' => 'required|string',
            'tempField' => 'required|integer|min:1|max:8',
            'humidityField' => 'required|integer|min:1|max:8',
            'phField' => 'required|integer|min:1|max:8',
            'gasField' => 'required|integer|min:1|max:8',
            'resultsCount' => 'required|integer|min:1|max:100',
        ]);
        
        // Update the user's settings in the database
        $user = auth()->user();
        $user->thingspeak_settings = json_encode($validated);
       
        
        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully',
            'settings' => $validated
        ]);
    }
    
    /**
     * Get current settings
     */
    public function getSettings()
    {
        $user = auth()->user();
        $settings = $user->thingspeak_settings ? json_decode($user->thingspeak_settings, true) : $this->thingspeakConfig;
        
        return response()->json([
            'status' => 'success',
            'settings' => $settings
        ]);
    }
}