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

$user_data = null;
$error_message = '';
$success_message = '';

// Get user data from database
try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        $error_message = "User data not found.";
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $error_message = "Error: " . $e->getMessage();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    try {
        $conn = new mysqli($host, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $location = $conn->real_escape_string($_POST['location']);
        $zip_code = $conn->real_escape_string($_POST['zip_code']);
        $preferred_city = $conn->real_escape_string($_POST['preferred_city']);
        
        $update_sql = "UPDATE users SET full_name = ?, location = ?, zip_code = ?, preferred_city = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $full_name, $location, $zip_code, $preferred_city, $user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $_SESSION['user_name'] = $full_name;
            $user_data['full_name'] = $full_name;
            $user_data['location'] = $location;
            $user_data['zip_code'] = $zip_code;
            $user_data['preferred_city'] = $preferred_city;
        } else {
            $error_message = "Error updating profile.";
        }
        
        $update_stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Air Quality Monitor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --accent-color: #2ecc71;
            --accent-dark: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --text-dark: #2c3e50;
            --text-light: #ecf0f1;
            --bg-light: #f8f9fa;
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .navbar-nav {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-btn {
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn.primary {
            background: var(--primary-color);
            color: white;
        }

        .nav-btn.danger {
            background: var(--danger-color);
            color: white;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .profile-email {
            color: #666;
            font-size: 1.1rem;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f2f6;
        }

        .card-header h3 {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-header i {
            font-size: 1.5rem;
            color: var(--accent-color);
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 1;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-value {
            flex: 2;
            color: #555;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-success {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-dark));
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .registration-date {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            .profile-content {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 0.5rem;
            }

            .profile-header,
            .profile-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="request.php" class="navbar-brand">
            <i class="fas fa-wind"></i>
            Air Quality Monitor
        </a>
        <div class="navbar-nav">
            <a href="request.php" class="nav-btn primary">
                <i class="fas fa-dashboard"></i>
                Dashboard
            </a>
            <a href="logout.php" class="nav-btn danger">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </nav>

    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($user_data): ?>
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h1 class="profile-name"><?php echo htmlspecialchars($user_data['full_name']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user_data['email']); ?></p>
                <?php if (isset($user_data['created_at'])): ?>
                    <div class="registration-date">
                        <i class="fas fa-calendar-alt"></i>
                        Member since <?php echo date('F Y', strtotime($user_data['created_at'])); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-content">
                <!-- Profile Information -->
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Profile Information</h3>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user"></i>
                            Full Name
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user_data['full_name']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user_data['email']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Location
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user_data['location']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-map-pin"></i>
                            Zip Code
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user_data['zip_code']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-city"></i>
                            Preferred City
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user_data['preferred_city']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-check-circle"></i>
                            Terms Accepted
                        </div>
                        <div class="info-value">
                            <?php if ($user_data['terms_accepted']): ?>
                                <span style="color: var(--accent-color);">
                                    <i class="fas fa-check"></i> Yes
                                </span>
                            <?php else: ?>
                                <span style="color: var(--danger-color);">
                                    <i class="fas fa-times"></i> No
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Form -->
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-edit"></i>
                        <h3>Edit Profile</h3>
                    </div>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="full_name">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input type="text" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">
                                <i class="fas fa-map-marker-alt"></i> Location
                            </label>
                            <input type="text" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($user_data['location']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="zip_code">
                                <i class="fas fa-map-pin"></i> Zip Code
                            </label>
                            <input type="text" id="zip_code" name="zip_code" 
                                   value="<?php echo htmlspecialchars($user_data['zip_code']); ?>" 
                                   pattern="\d{4}" maxlength="4" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred_city">
                                <i class="fas fa-city"></i> Preferred City
                            </label>
                            <select id="preferred_city" name="preferred_city" required>
                                <option value="Dhaka" <?php echo ($user_data['preferred_city'] == 'Dhaka') ? 'selected' : ''; ?>>Dhaka</option>
                                <option value="Chittagong" <?php echo ($user_data['preferred_city'] == 'Chittagong') ? 'selected' : ''; ?>>Chittagong</option>
                                <option value="Khulna" <?php echo ($user_data['preferred_city'] == 'Khulna') ? 'selected' : ''; ?>>Khulna</option>
                                <option value="Rangpur" <?php echo ($user_data['preferred_city'] == 'Rangpur') ? 'selected' : ''; ?>>Rangpur</option>
                                <option value="Rajshahi" <?php echo ($user_data['preferred_city'] == 'Rajshahi') ? 'selected' : ''; ?>>Rajshahi</option>
                                <option value="Barishal" <?php echo ($user_data['preferred_city'] == 'Barishal') ? 'selected' : ''; ?>>Barishal</option>
                                <option value="Comilla" <?php echo ($user_data['preferred_city'] == 'Comilla') ? 'selected' : ''; ?>>Comilla</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="profile-card">
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Unable to load profile data. Please try again later.
                </div>
                <a href="request.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate profile cards on load
            const cards = document.querySelectorAll('.profile-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>