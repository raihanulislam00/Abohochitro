<?php
// Test file to check OpenWeatherMap API

function testWeatherAPI($city) {
    $apiKey = 'b504184017f69a631dde123cfaf9ede0';
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city},BD&units=metric&appid={$apiKey}";
    
    echo "<h3>Testing API for: {$city}</h3>";
    echo "<p>API URL: {$apiUrl}</p>";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "<p style='color: red;'>CURL Error: " . curl_error($ch) . "</p>";
        curl_close($ch);
        return;
    }
    
    curl_close($ch);
    $data = json_decode($response, true);
    
    echo "<p>Response Status: " . (isset($data['cod']) ? $data['cod'] : 'Unknown') . "</p>";
    
    if (isset($data['cod']) && $data['cod'] == 200) {
        echo "<p style='color: green;'>✅ API Call Successful!</p>";
        echo "<p>Temperature: " . (isset($data['main']['temp']) ? round($data['main']['temp']) . "°C" : "Not available") . "</p>";
        echo "<p>Humidity: " . (isset($data['main']['humidity']) ? $data['main']['humidity'] . "%" : "Not available") . "</p>";
        echo "<p>Pressure: " . (isset($data['main']['pressure']) ? $data['main']['pressure'] . " hPa" : "Not available") . "</p>";
        echo "<p>Weather: " . (isset($data['weather'][0]['description']) ? $data['weather'][0]['description'] : "Not available") . "</p>";
    } else {
        echo "<p style='color: red;'>❌ API Error: " . (isset($data['message']) ? $data['message'] : 'Unknown error') . "</p>";
    }
    
    echo "<hr>";
}

// Test a few cities
$testCities = ["Dhaka", "Tangail", "Rajshahi"];

echo "<h2>OpenWeatherMap API Test</h2>";
echo "<p>Testing API connectivity and data retrieval...</p>";

foreach ($testCities as $city) {
    testWeatherAPI($city);
}

echo "<h3>Raw Response Example (Dhaka):</h3>";
$apiKey = 'b504184017f69a631dde123cfaf9ede0';
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=Dhaka,BD&units=metric&appid={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

echo "<pre>" . htmlspecialchars($response) . "</pre>";
?> 