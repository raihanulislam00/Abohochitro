<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = '';

$rememberedEmail = '';
$loginError = '';

// Check remembered email
if (isset($_COOKIE['remembered_email'])) {
    $rememberedEmail = htmlspecialchars($_COOKIE['remembered_email']);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        $loginError = "Connection failed: " . $conn->connect_error;
    } else {
        $email = $conn->real_escape_string($_POST['login_email']);
        $password = $_POST['login_password'];
        $remember = isset($_POST['remember']) ? true : false;

        // Find user in database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                
                // Remember email if requested
                if ($remember) {
                    $expire = time() + 30 * 24 * 60 * 60;
                    setcookie('remembered_email', $email, $expire, '/');
                } else {
                    setcookie('remembered_email', '', time() - 3600, '/');
                }
                
                header("Location: request.php");
                exit();
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "Invalid email or password.";
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Quality & Health Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a2a6c, #2c3e50);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            padding: 20px;
            border-radius: 15px;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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
            max-width: 800px;
            margin: 0 auto;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
        }

        .box {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .box h2, .box h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .box h2 i, .box h3 i {
            color: #3498db;
        }

        .health-tips-section {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .health-tips-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .health-tips-header h2 {
            color: white;
            margin: 0;
            font-size: 28px;
        }

        .health-tips-header i {
            font-size: 32px;
            color: #f39c12;
        }

        .group-members-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .group-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .group-header h3 {
            margin: 0;
            color: white;
            font-size: 22px;
        }

        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .member-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .member-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .member-name {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 18px;
        }

        .member-id {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .checkbox-container input {
            width: auto;
        }

        .checkbox-container label {
            margin: 0;
            font-weight: normal;
        }

        .checkbox-container a {
            color: #3498db;
            text-decoration: none;
        }

        .checkbox-container a:hover {
            text-decoration: underline;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .login-container {
            margin-top: 25px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }

        .form-footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .color-picker-container {
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .color-picker-container:hover {
            border-color: #3498db;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
        }

        .color-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .color-preview {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .color-preview:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        #fav_color {
            position: absolute;
            opacity: 0;
            width: 55px;
            height: 55px;
            cursor: pointer;
        }

        .color-info {
            flex: 1;
        }

        .color-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .color-hex {
            font-size: 14px;
            color: #6c757d;
            font-family: 'Courier New', monospace;
            background: rgba(0, 0, 0, 0.05);
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        /* Real-time Widget Styles */
        .real-time-widget {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .location {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .location h3 {
            color: white;
            margin: 0;
            font-size: 1.4rem;
        }

        .location i {
            color: #f39c12;
            font-size: 1.2rem;
        }

        .aqi-status {
            text-align: center;
            background: rgba(46, 204, 113, 0.3);
            padding: 8px 15px;
            border-radius: 20px;
            border: 2px solid #2ecc71;
        }

        .aqi-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            display: block;
            line-height: 1;
        }

        .aqi-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .widget-body {
            display: grid;
            gap: 15px;
        }

        .widget-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .metric {
            background: rgba(0, 0, 0, 0.2);
            padding: 12px;
            border-radius: 8px;
            position: relative;
        }

        .metric-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
        }

        .metric-status {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 1.2rem;
        }

        .metric-status.good { color: #2ecc71; }
        .metric-status.warning { color: #f39c12; }

        .trend {
            font-size: 0.8rem;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.8);
        }

        .trend.up { color: #2ecc71; }
        .trend.down { color: #e74c3c; }
        
        .time-chart {
            margin-top: 20px;
            padding: 15px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        
        .chart-title {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .chart-container {
            display: flex;
            height: 100px;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }
        
        .chart-bar {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .chart-value {
            background: rgba(52, 152, 219, 0.7);
            width: 100%;
            border-radius: 4px 4px 0 0;
            transition: height 0.5s ease;
        }
        
        .chart-time {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
            text-align: center;
        }
        
        .chart-value-label {
            font-size: 0.7rem;
            color: white;
            text-align: center;
            margin-top: 3px;
        }

        /* 7-Day Forecast Widget Styles */
        .forecast-widget {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .forecast-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .forecast-header h3 {
            color: white;
            margin: 0;
            font-size: 1.4rem;
        }

        .forecast-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 12px;
        }

        .forecast-day {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        .forecast-date {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
        }

        .forecast-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: white;
        }

        .forecast-temp {
            font-weight: 600;
            color: white;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .forecast-desc {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }

        @media (max-width: 992px) {
            .container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }
            
            .health-tips-header h2 {
                font-size: 24px;
            }
            
            .members-grid {
                grid-template-columns: 1fr;
            }
            
            .widget-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <header>
            <h1><i class="fas fa-wind"></i> Air Quality & Health Dashboard</h1>
            <p>Comprehensive health guidance for air quality conditions in Bangladesh</p>
        </header>

        <div class="container">
            <div class="left-panel">
                <div class="box box-1">
                    <div class="health-tips-section">
                        <div class="health-tips-header">
                            <i class="fas fa-heartbeat fa-2x"></i>
                            <h2>Air Quality & Health Dashboard</h2>
                        </div>
                        
                        <!-- Real-time Air Quality Widget -->
                        <div class="real-time-widget">
                            <div class="widget-header">
                                <div class="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h3>Badda Thana, Dhaka</h3>
                                </div>
                                <div class="aqi-status">
                                    <span class="aqi-value">3</span>
                                    <span class="aqi-label">Low (Good)</span>
                                </div>
                            </div>
                            
                            <div class="widget-body">
                                <div class="widget-row">
                                    <div class="metric">
                                        <div class="metric-label">UV Index</div>
                                        <div class="metric-value">Low</div>
                                        <div class="metric-status good">✅</div>
                                    </div>
                                    <div class="metric">
                                        <div class="metric-label">Humidity</div>
                                        <div class="metric-value">87%</div>
                                        <div class="metric-status warning">⚠️</div>
                                    </div>
                                </div>
                                
                                <div class="widget-row">
                                    <div class="metric">
                                        <div class="metric-label">Wind</div>
                                        <div class="metric-value">13 km/h</div>
                                    </div>
                                    <div class="metric">
                                        <div class="metric-label">Temperature</div>
                                        <div class="metric-value">26°C</div>
                                    </div>
                                </div>
                                
                                <div class="widget-row">
                                    <div class="metric">
                                        <div class="metric-label">Pressure</div>
                                        <div class="metric-value">4.83 hPa</div>
                                        <div class="trend up">↑ Rising</div>
                                    </div>
                                    <div class="metric">
                                        <div class="metric-label">Visibility</div>
                                        <div class="metric-value">Good</div>
                                    </div>
                                </div>
                                
                                <!-- Time chart for past hours -->
                                <div class="time-chart">
                                    <div class="chart-title">
                                        <i class="fas fa-chart-line"></i>
                                        <span>Air Quality Trend (Past 6 Hours)</span>
                                    </div>
                                    <div class="chart-container" id="aqiChart">
                                        <!-- Chart will be generated by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 7-Day Forecast Widget -->
                        <div class="forecast-widget">
                            <div class="forecast-header">
                                <i class="fas fa-calendar-alt"></i>
                                <h3>7-Day Forecast</h3>
                            </div>
                            <div class="forecast-container" id="forecastContainer">
                                <!-- Forecast will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box box-2">
                    <div class="group-members-section">
                        <div class="group-header">
                            <i class="fas fa-users fa-2x"></i>
                            <h3>Project Team Members</h3>
                        </div>
                        <div class="members-grid">
                            <div class="member-card">
                                <div class="member-photo">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-name">RAIHANUL ISLAM</div>
                                <div class="member-id">ID: 22-46680-1</div>
                                <div class="member-role">Air Quality Analyst</div>
                            </div>
                            
                            <div class="member-card">
                                <div class="member-photo">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-name">SHAHRIAR REZA</div>
                                <div class="member-id">ID: 22-46673-1</div>
                                <div class="member-role">Health Impact Researcher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-section">
                <div class="form-container">
                    <h2><i class="fas fa-user-plus"></i> Create Account</h2>
                    <form action="process.php" method="POST">
                        <div class="form-group">
                            <label for="fname"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" id="fname" name="fname" placeholder="Enter your full name">
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="email" name="email" placeholder="xx-xxxxx-x@student.aiub.edu">
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="password" name="password" placeholder="At least 8 characters">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"><i class="fas fa-check-circle"></i> Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password">
                        </div>
                        <div class="form-group">
                            <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                            <input type="text" id="location" name="location" placeholder="Your current location">
                        </div>
                        <div class="form-group">
                            <label for="zip"><i class="fas fa-map-pin"></i> Zip Code</label>
                            <input type="text" id="zip" name="zip" placeholder="4-digit zip code">
                        </div>
                        <div class="form-group">
                            <label for="city"><i class="fas fa-city"></i> Preferred City</label>
                            <select id="city" name="city">
                                <option value="">Select City</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Barishal">Barishal</option>
                                <option value="Comilla">Comilla</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fav_color"><i class="fas fa-palette"></i> Dashboard Color</label>
                            <div class="color-picker-container">
                                <div class="color-input-wrapper">
                                    <div class="color-preview" id="colorPreview" style="background-color: #3498db;">
                                        <input type="color" id="fav_color" name="fav_color" value="#3498db">
                                    </div>
                                    <div class="color-info">
                                        <div class="color-name" id="colorName">Sky Blue</div>
                                        <div class="color-hex" id="colorHex">#3498db</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="terms" name="terms">
                            <label for="terms">I agree to the <a href="#">terms and conditions</a></label>
                        </div>
                        <button type="submit"><i class="fas fa-paper-plane"></i> Create Account</button>
                    </form>
                </div>
                
                <div class="form-container login-container">
                    <h2><i class="fas fa-sign-in-alt"></i> Log In</h2>
                    <?php if (!empty($loginError)): ?>
                        <div class="error-message"><?php echo $loginError; ?></div>
                    <?php endif; ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group">
                            <label for="login_email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="login_email" name="login_email" placeholder="Enter your email" value="<?php echo $rememberedEmail; ?>">
                        </div>
                        <div class="form-group">
                            <label for="login_password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="login_password" name="login_password" placeholder="Enter your password">
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <button type="submit" name="login"><i class="fas fa-sign-in-alt"></i> Log In</button>
                    </form>
                    <div class="form-footer">
                        <p>Don't have an account? <a href="#" id="show-register">Register Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced color picker functionality
        const colorInput = document.getElementById('fav_color');
        const colorPreview = document.getElementById('colorPreview');
        const colorName = document.getElementById('colorName');
        const colorHex = document.getElementById('colorHex');
        
        // Color names for display
        const colorNames = {
            '#3498db': 'Sky Blue',
            '#1abc9c': 'Turquoise',
            '#9b59b6': 'Amethyst',
            '#e74c3c': 'Alizarin',
            '#f1c40f': 'Sunflower',
            '#2ecc71': 'Emerald',
            '#e67e22': 'Carrot',
            '#2c3e50': 'Midnight Blue'
        };
        
        function updateColorDisplay(color) {
            colorPreview.style.backgroundColor = color;
            colorHex.textContent = color.toUpperCase();
            colorName.textContent = colorNames[color.toLowerCase()] || 'Custom Color';
        }
        
        function setColor(color) {
            colorInput.value = color;
            updateColorDisplay(color);
        }
        
        colorInput.addEventListener('change', function() {
            updateColorDisplay(this.value);
        });
        
        // Initialize with default color
        updateColorDisplay('#3498db');
        
        // Function to update dashboard colors
        function updateDashboardColors(color) {
            document.documentElement.style.setProperty('--primary-color', color);
            
            // Update gradients and other color-related elements
            const elements = document.querySelectorAll('.health-tips-section, .group-members-section');
            elements.forEach(el => {
                el.style.background = `linear-gradient(135deg, ${color}, #2c3e50)`;
            });
        }
        
        // Update dashboard when color changes
        colorInput.addEventListener('input', function() {
            updateDashboardColors(this.value);
        });
        
        // Toggle between login and register forms
        document.getElementById('show-register').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.login-container').scrollIntoView({ behavior: 'smooth' });
        });
        
        // Generate AQI chart
        function generateAqiChart() {
            const chartContainer = document.getElementById('aqiChart');
            const hours = ['12am', '1am', '2am', '3am', '4am', '5am'];
            const values = [0.23, 2.66, 2.93, 3.44, 3.74, 1.97];
            
            // Find max value for scaling
            const maxValue = Math.max(...values);
            
            chartContainer.innerHTML = '';
            
            hours.forEach((hour, index) => {
                const barHeight = (values[index] / maxValue) * 80; // 80 is max height in px
                
                const barElement = document.createElement('div');
                barElement.className = 'chart-bar';
                
                barElement.innerHTML = `
                    <div class="chart-value" style="height: ${barHeight}px"></div>
                    <div class="chart-value-label">${values[index]}</div>
                    <div class="chart-time">${hour}</div>
                `;
                
                chartContainer.appendChild(barElement);
            });
        }
        
        // Simulate real-time updates
        function updateWeatherData() {
            // Generate random variations for demonstration
            const metrics = document.querySelectorAll('.metric-value');
            
            metrics.forEach(metric => {
                const label = metric.parentElement.querySelector('.metric-label').textContent;
                if (label === 'Temperature') {
                    const temp = 26 + (Math.random() - 0.5);
                    metric.textContent = temp.toFixed(1) + '°C';
                } else if (label === 'Humidity') {
                    const humidity = 87 + (Math.random() * 3 - 1.5);
                    metric.textContent = humidity.toFixed(0) + '%';
                } else if (label === 'Wind') {
                    const wind = 13 + (Math.random() * 4 - 2);
                    metric.textContent = wind.toFixed(1) + ' km/h';
                }
            });
        }
        
        // Fetch 7-day forecast
        async function fetchWeatherForecast() {
            const apiKey = 'bd5e378503939ddaee76f12ad7a97608';
            const city = 'Dhaka';
            const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${apiKey}`;
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.cod === '200') {
                    processForecastData(data.list);
                } else {
                    console.error('Error fetching forecast:', data.message);
                    // Show mock data if API fails
                    showMockForecast();
                }
            } catch (error) {
                console.error('API Error:', error);
                // Show mock data if API fails
                showMockForecast();
            }
        }
        
        function processForecastData(forecastList) {
            // Group forecasts by day
            const dailyForecasts = {};
            
            forecastList.forEach(item => {
                const date = new Date(item.dt * 1000);
                const dayKey = date.toLocaleDateString('en-US', { weekday: 'short' });
                
                if (!dailyForecasts[dayKey]) {
                    dailyForecasts[dayKey] = {
                        temp_min: item.main.temp_min,
                        temp_max: item.main.temp_max,
                        weather: item.weather[0],
                        date: dayKey
                    };
                } else {
                    // Update min/max temps
                    if (item.main.temp_min < dailyForecasts[dayKey].temp_min) {
                        dailyForecasts[dayKey].temp_min = item.main.temp_min;
                    }
                    if (item.main.temp_max > dailyForecasts[dayKey].temp_max) {
                        dailyForecasts[dayKey].temp_max = item.main.temp_max;
                    }
                }
            });
            
            displayForecast(Object.values(dailyForecasts).slice(0, 7));
        }
        
        function displayForecast(forecasts) {
            const container = document.getElementById('forecastContainer');
            container.innerHTML = '';
            
            // Weather icon mapping
            const iconMap = {
                '01d': 'fas fa-sun',
                '01n': 'fas fa-moon',
                '02d': 'fas fa-cloud-sun',
                '02n': 'fas fa-cloud-moon',
                '03d': 'fas fa-cloud',
                '03n': 'fas fa-cloud',
                '04d': 'fas fa-cloud',
                '04n': 'fas fa-cloud',
                '09d': 'fas fa-cloud-rain',
                '09n': 'fas fa-cloud-rain',
                '10d': 'fas fa-cloud-sun-rain',
                '10n': 'fas fa-cloud-moon-rain',
                '11d': 'fas fa-bolt',
                '11n': 'fas fa-bolt',
                '13d': 'fas fa-snowflake',
                '13n': 'fas fa-snowflake',
                '50d': 'fas fa-smog',
                '50n': 'fas fa-smog'
            };
            
            forecasts.forEach(day => {
                const forecastElement = document.createElement('div');
                forecastElement.className = 'forecast-day';
                
                forecastElement.innerHTML = `
                    <div class="forecast-date">${day.date}</div>
                    <div class="forecast-icon">
                        <i class="${iconMap[day.weather.icon] || 'fas fa-cloud'}"></i>
                    </div>
                    <div class="forecast-temp">${Math.round(day.temp_max)}°/${Math.round(day.temp_min)}°</div>
                    <div class="forecast-desc">${day.weather.description}</div>
                `;
                
                container.appendChild(forecastElement);
            });
        }
        
        // Show mock forecast data if API fails
        function showMockForecast() {
            const container = document.getElementById('forecastContainer');
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const forecasts = [
                { temp: '32°/26°', icon: 'fas fa-sun', desc: 'Sunny' },
                { temp: '31°/25°', icon: 'fas fa-cloud-sun', desc: 'Partly cloudy' },
                { temp: '30°/24°', icon: 'fas fa-cloud', desc: 'Cloudy' },
                { temp: '29°/23°', icon: 'fas fa-cloud-rain', desc: 'Light rain' },
                { temp: '30°/24°', icon: 'fas fa-cloud-sun', desc: 'Partly cloudy' },
                { temp: '31°/25°', icon: 'fas fa-sun', desc: 'Sunny' },
                { temp: '32°/26°', icon: 'fas fa-sun', desc: 'Sunny' }
            ];
            
            container.innerHTML = '';
            
            days.forEach((day, index) => {
                const forecast = forecasts[index];
                const forecastElement = document.createElement('div');
                forecastElement.className = 'forecast-day';
                
                forecastElement.innerHTML = `
                    <div class="forecast-date">${day}</div>
                    <div class="forecast-icon">
                        <i class="${forecast.icon}"></i>
                    </div>
                    <div class="forecast-temp">${forecast.temp}</div>
                    <div class="forecast-desc">${forecast.desc}</div>
                `;
                
                container.appendChild(forecastElement);
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            generateAqiChart();
            fetchWeatherForecast();
            
            // Update every 30 seconds for demo purposes
            setInterval(updateWeatherData, 30000);
        });
    </script>
</body>
</html>