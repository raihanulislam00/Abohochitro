<?php
// update_weather.php

header('Content-Type: application/json');

// Function to get weather data from OpenWeatherMap API
function getWeatherData($city) {
    // Updated API key
    $apiKey = 'b504184017f69a631dde123cfaf9ede0';
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city},BD&units=metric&appid={$apiKey}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout to 10 seconds
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Add this for SSL issues
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        // Return fallback data instead of error
        return getFallbackWeatherData($city);
    }
    
    curl_close($ch);
    $data = json_decode($response, true);
    
    $result = ['temp' => '--', 'status' => 'success'];
    
    // Check if API returned an error
    if (isset($data['cod']) && $data['cod'] != 200) {
        $result['status'] = 'error';
        $result['error'] = isset($data['message']) ? $data['message'] : 'API Error';
        // Return fallback data instead of error
        return getFallbackWeatherData($city);
    }
    
    // Get temperature
    if (isset($data['main']['temp'])) {
        $result['temp'] = round($data['main']['temp']) . "°C";
    }
    
    // Add additional weather info
    if (isset($data['weather'][0]['description'])) {
        $result['description'] = ucfirst($data['weather'][0]['description']);
    }
    
    if (isset($data['main']['feels_like'])) {
        $result['feels_like'] = round($data['main']['feels_like']) . "°C";
    }
    
    return $result;
}

// Fallback weather data function
function getFallbackWeatherData($city) {
    // Fallback temperature data for major cities in Bangladesh
    $fallbackTemps = [
        'Dhaka' => '32°C',
        'Tangail' => '29°C',
        'Rajshahi' => '33°C',
        'Khulna' => '32°C',
        'Sylhet' => '28°C',
        'Barisal' => '30°C',
        'Rangpur' => '30°C',
        'Mymensingh' => '27°C',
        'Gazipur' => '32°C',
        'Cox\'s Bazar' => '29°C'
    ];
    
    return [
        'temp' => isset($fallbackTemps[$city]) ? $fallbackTemps[$city] : '--',
        'feels_like' => isset($fallbackTemps[$city]) ? $fallbackTemps[$city] : '--',
        'status' => 'fallback'
    ];
}

// List of cities
$cities = [
    "Dhaka", "Tangail", "Rajshahi", "Khulna", "Sylhet",
    "Barisal", "Rangpur", "Mymensingh", "Gazipur", "Cox's Bazar"
];

$weatherData = [];
foreach ($cities as $city) {
    $key = strtolower(str_replace([' ', "'"], ['-', ''], $city));
    $weatherData[$key] = getWeatherData($city);
}

echo json_encode($weatherData);
?>