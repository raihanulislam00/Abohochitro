<?php
// update_weather.php

// Function to get weather data from OpenWeatherMap API
function getWeatherData($city) {
    $apiKey = 'e7a5098178a2a0b2ee96840ed9248968';
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city},BD&units=metric&appid={$apiKey}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        return ['temp' => '--', 'aqi' => '--'];
    }
    
    curl_close($ch);
    $data = json_decode($response, true);
    
    $result = ['temp' => '--', 'aqi' => '--'];
    
    // Get temperature
    if (isset($data['main']['temp'])) {
        $result['temp'] = round($data['main']['temp']) . "°C";
    }
    
    // Get air quality (if available)
    if (isset($data['air_quality']['aqi'])) {
        $result['aqi'] = $data['air_quality']['aqi'];
    } elseif (isset($data['main']['humidity'])) {
        // Simulate AQI based on humidity
        $aqi = round(($data['main']['humidity'] / 100) * 150);
        $result['aqi'] = $aqi;
    }
    
    return $result;
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

header('Content-Type: application/json');
echo json_encode($weatherData);
?>