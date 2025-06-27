<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database configuration
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = '';

// Get user data from database
$conn = new mysqli($host, $username, $password, $dbname);
$user_data = null;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
}

$stmt->close();
$conn->close();

// Get user's favorite color from cookie
$fav_color = isset($_COOKIE['fav_color']) ? $_COOKIE['fav_color'] : '#ff9a00';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangladesh Temperature Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* NAVBAR AT TOP OF PAGE */
        .top-navbar {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            position: sticky;
            top: 10px;
            z-index: 100;
        }
        
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff9a00, #ff6a00);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .welcome-text h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.5rem;
        }
        
        .welcome-text p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .user-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .nav-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .cyclone-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .cyclone-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .air-quality-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .air-quality-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .profile-btn {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            background: linear-gradient(135deg, #ff9a00 0%, #ff6a00 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .temperature-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .overview-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .overview-card:hover {
            transform: translateY(-5px);
        }

        .overview-card h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .overview-temp {
            font-size: 3rem;
            font-weight: bold;
            margin: 15px 0;
        }

        .overview-desc {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .regions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
        }

        .region-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .region-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .region-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .region-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .region-header h3 {
            font-size: 1.4rem;
            color: #2c3e50;
            margin: 0;
        }

        .cities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .city-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .city-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .city-item:hover::before {
            left: 100%;
        }

        .city-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .city-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .city-temp {
            font-size: 2.2rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }

        .city-details {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .city-feels-like {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .city-humidity {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #3498db;
        }

        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
        }

        .refresh-button {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .refresh-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
        }

        .refresh-button:active {
            transform: translateY(0);
        }

        .last-updated {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .loading-indicator {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stats-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .temperature-range {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .temp-hot { color: #e74c3c; }
        .temp-warm { color: #f39c12; }
        .temp-mild { color: #27ae60; }
        .temp-cool { color: #3498db; }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            .overview-temp {
                font-size: 2.5rem;
            }
            
            .city-temp {
                font-size: 1.8rem;
            }
            
            .regions-container {
                grid-template-columns: 1fr;
            }
            
            .cities-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- NAVBAR AT THE TOP OF THE PAGE -->
        <div class="top-navbar">
            <div class="welcome-section">
                <div class="user-avatar">
                    <?php 
                    if ($user_data && !empty($user_data['full_name'])) {
                        echo strtoupper(substr($user_data['full_name'], 0, 1)); 
                    } else {
                        echo 'U';
                    }
                    ?>
                </div>
                <div class="welcome-text">
                    <h2>Welcome back, <?php echo $user_data ? htmlspecialchars($user_data['full_name']) : 'User'; ?>!</h2>
                    <p><i class="fas fa-envelope"></i> <?php echo $user_data ? htmlspecialchars($user_data['email']) : 'user@example.com'; ?></p>
                </div>
            </div>
            <div class="user-actions">
                <a href="Cyclone.php" class="nav-btn cyclone-btn">
                    <i class="fas fa-hurricane"></i> Cyclone Track
                </a>
                <a href="request.php" class="nav-btn air-quality-btn">
                    <i class="fas fa-wind"></i> Air Quality
                </a>
                <a href="profile.php" class="nav-btn profile-btn">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
                <a href="logout.php" class="nav-btn logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- BANNER SECTION -->
        <header>
            <h1><i class="fas fa-thermometer-half"></i> Bangladesh Temperature Dashboard</h1>
            <p>Real-time temperature monitoring across all major cities and regions</p>
        </header>

        <div class="stats-summary">
            <h2><i class="fas fa-chart-line"></i> Temperature Overview</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="totalCities">58</div>
                    <div class="stat-label">Cities Monitored</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="avgTemp">31°C</div>
                    <div class="stat-label">Average Temperature</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="maxTemp">34°C</div>
                    <div class="stat-label">Highest Temperature</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="minTemp">26°C</div>
                    <div class="stat-label">Lowest Temperature</div>
                </div>
            </div>
            <div class="temperature-range">
                <p><strong>Temperature Range Guide:</strong></p>
                <p>
                    <span class="temp-hot">Hot (32°C+)</span> • 
                    <span class="temp-warm">Warm (28-31°C)</span> • 
                    <span class="temp-mild">Mild (24-27°C)</span> • 
                    <span class="temp-cool">Cool (Below 24°C)</span>
                </p>
            </div>
        </div>

        <div class="temperature-overview">
            <div class="overview-card">
                <h3><i class="fas fa-city"></i> Major Cities</h3>
                <div class="overview-temp" id="majorCitiesAvg">31°C</div>
                <div class="overview-desc">8 Metropolitan Areas</div>
            </div>
            
            <div class="overview-card">
                <h3><i class="fas fa-anchor"></i> Coastal Regions</h3>
                <div class="overview-temp" id="coastalAvg">30°C</div>
                <div class="overview-desc">10 Coastal Cities</div>
            </div>
            
            <div class="overview-card">
                <h3><i class="fas fa-mountain"></i> Hill Districts</h3>
                <div class="overview-temp" id="hillAvg">26°C</div>
                <div class="overview-desc">3 Hill Areas</div>
            </div>
            
            <div class="overview-card">
                <h3><i class="fas fa-map-marker-alt"></i> Rural Areas</h3>
                <div class="overview-temp" id="ruralAvg">31°C</div>
                <div class="overview-desc">37 Districts</div>
            </div>
        </div>

        <div class="controls">
            <div class="last-updated">
                <i class="fas fa-clock"></i>
                <span id="lastUpdated">Last updated: Loading...</span>
            </div>
            <button class="refresh-button" onclick="refreshTemperatureData()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>

        <div class="regions-container" id="regionsContainer">
            <!-- Regions will be populated by JavaScript -->
        </div>
    </div>

    <script>
        // Comprehensive temperature data for Bangladesh cities
        const temperatureData = {
            'Major Cities': {
                icon: 'fas fa-city',
                cities: {
                    'Dhaka': { temp: 32, feelsLike: 35, humidity: 75 },
                    'Chittagong': { temp: 30, feelsLike: 33, humidity: 80 },
                    'Khulna': { temp: 32, feelsLike: 34, humidity: 78 },
                    'Rajshahi': { temp: 33, feelsLike: 36, humidity: 65 },
                    'Sylhet': { temp: 28, feelsLike: 31, humidity: 85 },
                    'Barisal': { temp: 30, feelsLike: 33, humidity: 82 },
                    'Rangpur': { temp: 30, feelsLike: 32, humidity: 70 },
                    'Mymensingh': { temp: 27, feelsLike: 30, humidity: 88 }
                }
            },
            'Central Region': {
                icon: 'fas fa-map-marker-alt',
                cities: {
                    'Comilla': { temp: 31, feelsLike: 34, humidity: 77 },
                    'Gazipur': { temp: 32, feelsLike: 35, humidity: 74 },
                    'Narayanganj': { temp: 33, feelsLike: 36, humidity: 72 },
                    'Tangail': { temp: 29, feelsLike: 32, humidity: 80 },
                    'Manikganj': { temp: 32, feelsLike: 35, humidity: 76 },
                    'Munshiganj': { temp: 33, feelsLike: 36, humidity: 73 },
                    'Faridpur': { temp: 32, feelsLike: 35, humidity: 75 },
                    'Madaripur': { temp: 32, feelsLike: 34, humidity: 78 }
                }
            },
            'Northern Region': {
                icon: 'fas fa-mountain',
                cities: {
                    'Bogura': { temp: 31, feelsLike: 34, humidity: 68 },
                    'Dinajpur': { temp: 29, feelsLike: 32, humidity: 72 },
                    'Thakurgaon': { temp: 30, feelsLike: 33, humidity: 70 },
                    'Panchagarh': { temp: 29, feelsLike: 31, humidity: 75 },
                    'Nilphamari': { temp: 29, feelsLike: 32, humidity: 73 },
                    'Lalmonirhat': { temp: 30, feelsLike: 33, humidity: 71 },
                    'Kurigram': { temp: 30, feelsLike: 32, humidity: 74 },
                    'Gaibandha': { temp: 31, feelsLike: 34, humidity: 69 }
                }
            },
            'Eastern Region': {
                icon: 'fas fa-tree',
                cities: {
                    'Habiganj': { temp: 28, feelsLike: 31, humidity: 86 },
                    'Moulvibazar': { temp: 27, feelsLike: 30, humidity: 88 },
                    'Sunamganj': { temp: 28, feelsLike: 31, humidity: 87 },
                    'Brahmanbaria': { temp: 30, feelsLike: 33, humidity: 79 },
                    'Jamalpur': { temp: 28, feelsLike: 31, humidity: 83 },
                    'Kishoreganj': { temp: 29, feelsLike: 32, humidity: 81 },
                    'Netrokona': { temp: 27, feelsLike: 30, humidity: 89 },
                    'Sherpur': { temp: 28, feelsLike: 31, humidity: 84 }
                }
            },
            'Western Region': {
                icon: 'fas fa-water',
                cities: {
                    'Jessore': { temp: 32, feelsLike: 35, humidity: 76 },
                    'Kushtia': { temp: 33, feelsLike: 36, humidity: 67 },
                    'Jhenaidah': { temp: 33, feelsLike: 36, humidity: 68 },
                    'Magura': { temp: 32, feelsLike: 35, humidity: 74 },
                    'Narail': { temp: 31, feelsLike: 34, humidity: 77 },
                    'Chuadanga': { temp: 34, feelsLike: 37, humidity: 65 },
                    'Meherpur': { temp: 33, feelsLike: 36, humidity: 66 },
                    'Pabna': { temp: 31, feelsLike: 34, humidity: 71 },
                    'Sirajganj': { temp: 30, feelsLike: 33, humidity: 73 }
                }
            },
            'Coastal Region': {
                icon: 'fas fa-anchor',
                cities: {
                    'Cox\'s Bazar': { temp: 29, feelsLike: 32, humidity: 85 },
                    'Teknaf': { temp: 30, feelsLike: 33, humidity: 84 },
                    'Patuakhali': { temp: 31, feelsLike: 34, humidity: 83 },
                    'Bhola': { temp: 30, feelsLike: 33, humidity: 86 },
                    'Noakhali': { temp: 29, feelsLike: 32, humidity: 87 },
                    'Feni': { temp: 30, feelsLike: 33, humidity: 82 },
                    'Lakshmipur': { temp: 31, feelsLike: 34, humidity: 81 },
                    'Chandpur': { temp: 32, feelsLike: 35, humidity: 79 },
                    'Satkhira': { temp: 31, feelsLike: 34, humidity: 80 },
                    'Bagerhat': { temp: 30, feelsLike: 33, humidity: 84 }
                }
            },
            'Hill Districts': {
                icon: 'fas fa-mountain',
                cities: {
                    'Bandarban': { temp: 26, feelsLike: 28, humidity: 90 },
                    'Rangamati': { temp: 27, feelsLike: 29, humidity: 88 },
                    'Khagrachhari': { temp: 26, feelsLike: 28, humidity: 91 }
                }
            }
        };

        function getTemperatureClass(temp) {
            if (temp >= 32) return 'temp-hot';
            if (temp >= 28) return 'temp-warm';
            if (temp >= 24) return 'temp-mild';
            return 'temp-cool';
        }

        function renderRegions() {
            const container = document.getElementById('regionsContainer');
            container.innerHTML = '';

            Object.entries(temperatureData).forEach(([regionName, regionData]) => {
                const regionCard = document.createElement('div');
                regionCard.className = 'region-card';
                
                const citiesHtml = Object.entries(regionData.cities).map(([cityName, cityData]) => {
                    const tempClass = getTemperatureClass(cityData.temp);
                    return `
                        <div class="city-item">
                            <div class="city-name">${cityName}</div>
                            <div class="city-temp ${tempClass}">${cityData.temp}°C</div>
                            <div class="city-details">
                                <span class="city-feels-like">
                                    <i class="fas fa-thermometer-half"></i> ${cityData.feelsLike}°C
                                </span>
                                <span class="city-humidity">
                                    <i class="fas fa-tint"></i> ${cityData.humidity}%
                                </span>
                            </div>
                        </div>
                    `;
                }).join('');

                regionCard.innerHTML = `
                    <div class="region-header">
                        <div class="region-icon">
                            <i class="${regionData.icon}"></i>
                        </div>
                        <h3>${regionName}</h3>
                    </div>
                    <div class="cities-grid">
                        ${citiesHtml}
                    </div>
                `;

                container.appendChild(regionCard);
            });
        }

        function calculateStatistics() {
            let totalCities = 0;
            let totalTemp = 0;
            let maxTemp = -Infinity;
            let minTemp = Infinity;

            Object.values(temperatureData).forEach(region => {
                Object.values(region.cities).forEach(city => {
                    totalCities++;
                    totalTemp += city.temp;
                    maxTemp = Math.max(maxTemp, city.temp);
                    minTemp = Math.min(minTemp, city.temp);
                });
            });

            const avgTemp = Math.round(totalTemp / totalCities);

            document.getElementById('totalCities').textContent = totalCities;
            document.getElementById('avgTemp').textContent = avgTemp + '°C';
            document.getElementById('maxTemp').textContent = maxTemp + '°C';
            document.getElementById('minTemp').textContent = minTemp + '°C';
        }

        function updateLastUpdated() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('lastUpdated').textContent = `Last updated: ${timeString}`;
        }

        function refreshTemperatureData() {
            const button = document.querySelector('.refresh-button');
            const originalContent = button.innerHTML;
            
            button.innerHTML = '<div class="loading-indicator"></div> Refreshing...';
            button.disabled = true;

            // Simulate data refresh with slight temperature variations
            setTimeout(() => {
                Object.values(temperatureData).forEach(region => {
                    Object.values(region.cities).forEach(city => {
                        // Add small random variation (-2 to +2 degrees)
                        const variation = Math.floor(Math.random() * 5) - 2;
                        city.temp = Math.max(20, Math.min(40, city.temp + variation));
                        city.feelsLike = city.temp + Math.floor(Math.random() * 4) + 1;
                        city.humidity = Math.max(50, Math.min(95, city.humidity + Math.floor(Math.random() * 11) - 5));
                    });
                });

                renderRegions();
                calculateStatistics();
                updateLastUpdated();

                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);
        }

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            renderRegions();
            calculateStatistics();
            updateLastUpdated();
            
            // Auto-refresh every 5 minutes
            setInterval(updateLastUpdated, 60000);
        });
    </script>
</body>
</html>