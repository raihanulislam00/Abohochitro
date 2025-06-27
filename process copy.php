<?php
// Updated database configuration
$host = 'db.fr-pari1.bengt.wasmernet.com';
$port = 10272;
$dbname = 'dbSeNWLWvjfKnNgrsdtnqZF2';
$username = 'e3e76b65752d8000ddf0d635ed87';
$password = '0685e3e7-6b66-729b-8000-6f38cddbbad2';

// Create connection with port specification
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for display
$display_fname = $display_email = $display_location = $display_zip = $display_city = '';
$display_terms = 0;
$fav_color = '#3498db'; // Default color

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values for display (without escaping)
    $display_fname = $_POST["fname"];
    $display_email = $_POST["email"];
    $display_location = $_POST["location"];
    $display_zip = $_POST["zip"];
    $display_city = $_POST["city"];
    $display_terms = isset($_POST["terms"]) ? 1 : 0;
    $fav_color = $_POST["fav_color"] ?? '#3498db';

    // Set the favorite color as a cookie that expires in 30 days
    setcookie('fav_color', $fav_color, time() + (30 * 24 * 60 * 60), '/');
    
    // Escape values for database insertion
    $fname = $conn->real_escape_string($display_fname);
    $email = $conn->real_escape_string($display_email);
    $password_raw = $_POST["password"];
    $location = $conn->real_escape_string($display_location);
    $zip = $conn->real_escape_string($display_zip);
    $city = $conn->real_escape_string($display_city);
    $terms = $display_terms;

    // Hash password
    $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);
    $hashed_password = $conn->real_escape_string($hashed_password);

    // Create and execute query
    $sql = "INSERT INTO users (full_name, email, password, location, zip_code, preferred_city, terms_accepted)
            VALUES ('$fname', '$email', '$hashed_password', '$location', '$zip', '$city', $terms)";
    
    if (!$conn->query($sql)) {
        die("Error: " . $conn->error);
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful | Air Quality & Health Dashboard</title>
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
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, #3498db, #2ecc71);
        }

        .success-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .success-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 3.5rem;
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.4);
        }

        h2 {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #fff;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #3498db, #2ecc71);
            border-radius: 2px;
        }

        .welcome-message {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.9);
        }

        .health-benefits {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .health-benefits h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: #f39c12;
            font-size: 1.5rem;
        }

        .benefits-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
        }

        .benefit-item i {
            color: #2ecc71;
            font-size: 1.2rem;
            min-width: 24px;
            margin-top: 3px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-card h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: #3498db;
            font-size: 1.5rem;
        }

        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            flex: 1;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .value {
            flex: 2;
            color: rgba(255, 255, 255, 0.9);
        }

        .terms-agreed {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
        }

        .terms-agreed.yes {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        .terms-agreed.no {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
        }

        .color-display {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .button-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .button {
            flex: 1;
            min-width: 200px;
            padding: 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .back-button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .dashboard-button {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .back-button:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
        }

        .dashboard-button:hover {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }
            
            .info-row {
                flex-direction: column;
                gap: 5px;
                padding: 12px 0;
            }
            
            .label {
                margin-bottom: 5px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <div class="success-circle">
                <i class="fas fa-check"></i>
            </div>
        </div>
        
        <h2>Registration Successful!</h2>
        
        <div class="welcome-message">
            <p>Welcome to the Air Quality & Health Dashboard, <?php echo htmlspecialchars($display_fname); ?>!</p>
            <p>Your account has been created successfully. You can now access personalized air quality information and health recommendations.</p>
        </div>
        
        <div class="health-benefits">
            <h3><i class="fas fa-heartbeat"></i> Your Health Benefits</h3>
            <div class="benefits-list">
                <div class="benefit-item">
                    <i class="fas fa-bell"></i>
                    <div>Personalized air quality alerts for your location</div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-lungs"></i>
                    <div>Health recommendations based on current AQI levels</div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-mask"></i>
                    <div>Protection guidelines during high pollution periods</div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <div>Air quality information for your preferred city: <?php echo htmlspecialchars($display_city); ?></div>
                </div>
            </div>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-user-circle"></i> Account Information</h3>
            
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-user'></i> Full Name</div>";
                echo "<div class='value'>" . htmlspecialchars($display_fname) . "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-envelope'></i> Email</div>";
                echo "<div class='value'>" . htmlspecialchars($display_email) . "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-map-marker-alt'></i> Location</div>";
                echo "<div class='value'>" . htmlspecialchars($display_location) . "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-map-pin'></i> Zip Code</div>";
                echo "<div class='value'>" . htmlspecialchars($display_zip) . "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-city'></i> Preferred City</div>";
                echo "<div class='value'>" . htmlspecialchars($display_city) . "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-palette'></i> Dashboard Color</div>";
                echo "<div class='value'>";
                echo "<div class='color-display'>";
                echo "<div class='color-preview' style='background-color: $fav_color;'></div>";
                echo "<span>" . htmlspecialchars($fav_color) . "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                echo "<div class='info-row'>";
                echo "<div class='label'><i class='fas fa-file-contract'></i> Terms & Conditions</div>";
                if ($display_terms == 1) {
                    echo "<div class='value'><span class='terms-agreed yes'>Agreed</span></div>";
                } else {
                    echo "<div class='value'><span class='terms-agreed no'>Not Agreed</span></div>";
                }
                echo "</div>";
            } else {
                echo "<p>No data received. Please fill out the registration form.</p>";
            }
            ?>
        </div>
        
        <div class="button-container">
            <a href="request.php" class="button dashboard-button">
                <i class="fas fa-tachometer-alt"></i> Go to Login Page
            </a>
        </div>
    </div>
</body>
</html>