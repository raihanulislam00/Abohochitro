# 🌬️ Air Quality & Health Dashboard

A comprehensive web-based dashboard for monitoring air quality, temperature, and cyclone tracking in Bangladesh. This project provides real-time environmental data visualization and personalized health recommendations to help users make informed decisions about their health and safety.

## ✨ Features

### 🔐 User Authentication & Management
- **Secure Registration & Login**: Robust authentication system with encrypted password storage
- **Session Management**: Advanced session handling with "remember me" functionality
- **User Profiles**: Comprehensive profile management with customizable preferences
- **Personalized Experience**: Custom dashboard themes and location-based content

### 🌡️ Real-Time Air Quality Monitoring
- **Live AQI Data**: Real-time Air Quality Index monitoring for Bangladesh
- **Interactive Visualizations**: Dynamic charts and graphs powered by Chart.js
- **Location-Based Insights**: Localized air quality information for your area
- **Health Recommendations**: Personalized health advice based on current AQI levels
- **Smart Alerts**: Automated notifications during high pollution periods

### 🌡️ Temperature Dashboard
- **National Coverage**: Comprehensive temperature monitoring across Bangladesh
- **Historical Analysis**: Access to historical temperature data and trends
- **Regional Comparisons**: Side-by-side temperature analysis for different regions
- **Weather Patterns**: Advanced weather pattern analysis and forecasting
- **Seasonal Insights**: Detailed seasonal temperature trend visualization

### 🌀 Cyclone Tracking System
- **Real-Time Monitoring**: Live cyclone tracking with up-to-date position data
- **Interactive Maps**: Dynamic mapping powered by Leaflet.js technology
- **Satellite Integration**: High-resolution satellite imagery for accurate tracking
- **Path Predictions**: Advanced storm path prediction algorithms
- **Emergency Alerts**: Critical weather warnings and emergency preparedness information

### 🏥 Health & Safety Features
- **Personalized Recommendations**: Tailored health advice based on environmental conditions
- **Health Impact Analysis**: Detailed information on how air quality affects your health
- **Protection Guidelines**: Step-by-step protection measures during high pollution events
- **Respiratory Health**: Specialized tips for maintaining respiratory wellness
- **Emergency Preparedness**: Comprehensive emergency response information

### 📱 Responsive Design
- **Mobile-First**: Optimized for all devices with responsive design principles
- **Modern UI**: Beautiful gradient backgrounds and smooth animations
- **Interactive Elements**: Engaging user interface with intuitive navigation
- **Cross-Platform**: Compatible across all major browsers and operating systems
- **Accessibility**: Built with accessibility standards in mind

## 🎥 Demo

### 📹 Video Demonstration
**Watch the full demo of our Air Quality Dashboard in action:**

[![Air Quality Dashboard Demo](https://img.youtube.com/vi/LfcAvnbAchM/maxresdefault.jpg)](https://youtu.be/LfcAvnbAchM)

🔗 **[Watch on YouTube: Air Quality Dashboard Demo](https://youtu.be/LfcAvnbAchM)**

*This comprehensive video walkthrough showcases all the key features including user registration, air quality monitoring, temperature tracking, cyclone monitoring, and profile management.*

## 🚀 Installation

### Prerequisites
Make sure you have the following installed on your system:
- **PHP 7.4 or higher** with required extensions
- **MySQL 5.7 or higher** for database management
- **Apache/Nginx** web server
- **XAMPP/WAMP/MAMP** (recommended for local development)

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/air-quality-dashboard.git
cd air-quality-dashboard
```

### Step 2: Set Up Web Server
1. Copy all project files to your web server's document root directory
   - For XAMPP: `C:\xampp\htdocs\air-quality-dashboard\`
   - For WAMP: `C:\wamp64\www\air-quality-dashboard\`
2. Start your web server (Apache) and database server (MySQL)
3. Verify that PHP and MySQL services are running properly

### Step 3: Configure Database
Follow the comprehensive [Database Setup](#-database-setup) instructions below.

### Step 4: Access the Application
Open your web browser and navigate to:
```
http://localhost/air-quality-dashboard/
```

## 🗄️ Database Setup

### Step 1: Create Database
1. Open **phpMyAdmin** in your browser (`http://localhost/phpmyadmin/`)
2. Click on "New" to create a new database
3. Name the database `users` and click "Create"

### Step 2: Create Users Table
Execute the following SQL query in the SQL tab:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    zip_code VARCHAR(20),
    preferred_city VARCHAR(255),
    terms_accepted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Step 3: Database Configuration
Update the database connection settings in the following files:

**Files to update:**
- `index.php`
- `process.php`
- `request.php`
- `profile.php`
- `Temperature.php`
- `Cyclone.php`

**Configuration code:**
```php
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = ''; // Use your MySQL password if set
```

## 📖 Usage

### 1. Getting Started with Registration
1. Navigate to the homepage (`index.php`)
2. Click the **"Register"** button to create your account
3. Fill out the registration form with your details:
   - Full name and email address
   - Secure password (minimum 8 characters)
   - Location and zip code for personalized data
   - Choose your preferred dashboard color theme
4. Accept the terms and conditions
5. Click **"Submit"** to create your account

### 2. Logging Into Your Dashboard
1. Enter your registered email address and password
2. Check **"Remember me"** for convenient future access
3. Click **"Login"** to access your personalized dashboard

### 3. Navigating the Dashboard
- **🌬️ Air Quality Dashboard**: Monitor real-time AQI data and receive health recommendations
- **🌡️ Temperature Dashboard**: Access comprehensive temperature information and trends
- **🌀 Cyclone Tracking**: Track cyclone movements and receive emergency alerts
- **👤 Profile Management**: Update your account settings and preferences

### 4. Managing Your Profile
- Update personal information and contact details
- Change location preferences for localized data
- Customize your dashboard color theme
- View your account creation date and activity

## 📁 Project Structure

```
AQIBD/
├── 📄 index.php              # Main landing page with authentication
├── ⚙️ process.php            # User registration processing logic
├── 🌬️ request.php            # Air quality monitoring dashboard
├── 🌡️ Temperature.php        # Temperature monitoring interface
├── 🌀 Cyclone.php            # Cyclone tracking system
├── 👤 profile.php            # User profile management
├── ✅ confirm.php            # Registration confirmation page
├── 🚪 logout.php             # User logout functionality
├── 📊 show.php               # Data display utilities
├── 🎯 script.js              # Client-side JavaScript functionality
├── 🎨 style.css              # Main stylesheet and animations
├── 🖼️ images.png             # Project images and assets
└── 📖 README.md              # This documentation file
```

## 🛠️ Technologies Used

### Backend Technologies
- **PHP 7.4+**: Robust server-side scripting and business logic
- **MySQL**: Reliable relational database management
- **Apache/Nginx**: High-performance web server

### Frontend Technologies
- **HTML5**: Modern semantic markup structure
- **CSS3**: Advanced styling with animations and responsive design
- **JavaScript ES6+**: Interactive functionality and dynamic content
- **Chart.js**: Professional data visualization and charting
- **Leaflet.js**: Interactive mapping and geospatial visualization
- **Font Awesome**: Comprehensive icon library

### Development Tools & Libraries
- **Chart.js 3.x**: Advanced chart rendering and data visualization
- **Leaflet.js**: Open-source mapping library for interactive maps
- **Font Awesome 6.x**: Modern icon toolkit
- **Weather APIs**: Integration with multiple weather data providers

## 🔌 API Integration

The dashboard seamlessly integrates with multiple APIs to provide comprehensive environmental data:

### Air Quality Data Sources
- **OpenWeatherMap API**: Global air quality and pollution data
- **EPA AirNow API**: US Environmental Protection Agency data
- **Bangladesh DoE**: Local Department of Environment monitoring stations
- **AQICN**: World Air Quality Index project data

### Weather Information APIs
- **OpenWeatherMap**: Comprehensive weather and temperature data
- **WeatherAPI**: Detailed meteorological information
- **BMD Bangladesh**: Bangladesh Meteorological Department official data
- **AccuWeather**: Long-range weather forecasting

### Cyclone Tracking APIs
- **NASA Earth Data**: Satellite-based tropical cyclone tracking
- **JTWC**: Joint Typhoon Warning Center official data
- **BMD Cyclone**: Bangladesh Meteorological Department cyclone alerts
- **ECMWF**: European Centre for Medium-Range Weather Forecasts

## 🔒 Security Features

### Authentication & Authorization
- **Advanced Password Security**: Military-grade password hashing using PHP's `password_hash()`
- **Session Management**: Secure session handling with timeout protection
- **SQL Injection Prevention**: Parameterized queries and prepared statements
- **XSS Protection**: Complete input sanitization using `htmlspecialchars()`

### Data Protection
- **Input Validation**: Comprehensive server-side and client-side validation
- **CSRF Protection**: Cross-Site Request Forgery prevention measures
- **Secure Headers**: Implementation of security headers for enhanced protection
- **Database Security**: Encrypted database connections and secure credential storage

### Privacy & Compliance
- **Data Encryption**: User data protection through encryption
- **Session Security**: Secure cookie handling and session management
- **Privacy Compliance**: GDPR-ready privacy policy and data handling
- **Audit Logging**: Comprehensive activity logging for security monitoring

## 🤝 Contributing

We welcome and appreciate contributions from the community! Here's how you can help improve the Air Quality & Health Dashboard:

### How to Contribute
1. **Fork the Repository**: Click the "Fork" button on the GitHub repository
2. **Create a Feature Branch**: 
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. **Make Your Changes**: Implement your improvements or fixes
4. **Commit Your Changes**: 
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```
5. **Push to Your Branch**: 
   ```bash
   git push origin feature/AmazingFeature
   ```
6. **Open a Pull Request**: Submit your changes for review

### Development Guidelines
- **Code Standards**: Follow PHP PSR-12 coding standards for consistency
- **Documentation**: Write clear, comprehensive documentation for new features
- **Testing**: Thoroughly test all functionality before submitting
- **Security**: Ensure all contributions maintain security best practices
- **Performance**: Optimize code for performance and scalability

### Areas for Contribution
- **New Features**: Additional environmental monitoring capabilities
- **UI/UX Improvements**: Enhanced user interface and experience
- **API Integrations**: New data sources and API connections
- **Mobile Optimization**: Further mobile device enhancements
- **Accessibility**: Improved accessibility features and compliance

## 📄 License

This project is licensed under the **MIT License**. This means you are free to use, modify, and distribute this software, provided you include the original copyright notice and license terms.

For full license details, see the [LICENSE](LICENSE) file in the repository.

---

## 📞 Support & Contact

If you encounter any issues or have questions about the Air Quality & Health Dashboard:

- **🐛 Report Bugs**: Open an issue on GitHub
- **💡 Feature Requests**: Submit enhancement suggestions
- **📧 Contact**: Reach out through the repository's issue tracker
- **📚 Documentation**: Refer to this README for comprehensive guidance

---

**Made with ❤️ for environmental awareness and public health in Bangladesh**
