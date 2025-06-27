<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Confirmed | Air Quality Dashboard</title>
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
            position: relative;
            overflow: hidden;
        }

        /* Air quality particles animation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 50px;
            max-width: 700px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .confirmation-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }

        .icon-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            display: flex;
            justify-content: center;
            align-items: center;
            animation: pulse 2s infinite;
        }

        .icon-circle i {
            font-size: 60px;
            color: white;
        }

        .circle-outer {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 2px solid rgba(46, 204, 113, 0.5);
            border-radius: 50%;
            top: -10px;
            left: -10px;
            animation: pulseOuter 3s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }
            70% {
                box-shadow: 0 0 0 20px rgba(46, 204, 113, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        @keyframes pulseOuter {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            background: linear-gradient(to right, #3498db, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, #3498db, #2ecc71);
            border-radius: 2px;
        }

        .message {
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            padding: 25px;
            width: 180px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: rgba(255, 255, 255, 0.2);
        }

        .feature-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #f39c12;
        }

        .home-button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .home-button i {
            margin-right: 10px;
        }

        .home-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            transition: width 0.5s ease;
            z-index: -1;
        }

        .home-button:hover::before {
            width: 100%;
        }

        .home-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .quote {
            font-style: italic;
            margin-top: 40px;
            padding: 20px;
            border-left: 4px solid #3498db;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0 10px 10px 0;
            text-align: left;
        }

        .quote p {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .author {
            font-weight: 600;
            color: #3498db;
            text-align: right;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }
            
            h1 {
                font-size: 2.2rem;
            }
            
            .features {
                gap: 15px;
            }
            
            .feature-card {
                width: 100%;
                max-width: 250px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .message {
                font-size: 1rem;
            }
            
            .feature-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated particles for background -->
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="confirmation-icon">
            <div class="circle-outer"></div>
            <div class="icon-circle">
                <i class="fas fa-check"></i>
            </div>
        </div>
        
        <h1>Registration Confirmed!</h1>
        
        <div class="message">
            <p>Thank you for joining the Air Quality & Health Dashboard. Your registration is now complete.</p>
            <p>You'll receive personalized air quality alerts and health recommendations based on your preferences.</p>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lungs"></i>
                </div>
                <div class="feature-title">Health Insights</div>
                <div>Personalized health recommendations</div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="feature-title">Real-time Alerts</div>
                <div>AQI notifications for your area</div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="feature-title">Local Data</div>
                <div>Location-specific air quality</div>
            </div>
        </div>
        
        <div class="button-container">
            <a href="index.php" class="button back-button">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <a href="confirm.php" class="button dashboard-button">
                <i class="fas fa-tachometer-alt"></i> Confirm
            </a>
        </div>
        
        <div class="quote">
            <p>"Clean air is a basic human right. Protecting it is essential for our health and future generations."</p>
            <div class="author">- Air Quality Initiative</div>
        </div>
    </div>

    <script>
        // Create floating particles for background
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size (2px to 8px)
                const size = Math.random() * 6 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random animation duration (10s to 25s)
                const duration = Math.random() * 15 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // Random delay (0s to 5s)
                const delay = Math.random() * 5;
                particle.style.animationDelay = `${delay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Initialize particles on page load
        window.addEventListener('load', createParticles);
    </script>
    
</body>
</html>