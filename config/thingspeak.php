<?php

return [
    'channel_id' => env('THINGSPEAK_CHANNEL_ID', '2954806'),
    'api_key' => env('THINGSPEAK_API_KEY', 'SFWJ5UAULE9O6S0L'),
    'fields' => [
        'temperature' => 1,
        'humidity' => 2,
        'ph' => 3,
        'gas' => 4,
    ],
];