<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangladesh Cyclone Tracking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #1a73e8;
            --primary-dark: #0d5cb6;
            --accent-color: #34a853;
            --danger-color: #ea4335;
            --warning-color: #f9ab00;
            --text-dark: #202124;
            --text-light: #f8f9fa;
            --background-light: #f8f9fa;
            --background-dark: #34495e;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
            --satellite-blue: #1a73e8;
            --cyclone-purple: #8e44ad;
            --storm-orange: #e67e22;
            --nasa-red: #FC3D21;
            --nasa-blue: #0B3D91;
        }

        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* NAVBAR AT TOP OF PAGE */
        .top-navbar {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
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
            background: linear-gradient(45deg, var(--nasa-red), var(--nasa-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        .welcome-text h2 {
            margin: 0;
            color: #202124;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .welcome-text p {
            margin: 5px 0 0 0;
            color: #5f6368;
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
        
        .air-quality-btn {
            background: linear-gradient(135deg, #00bcd4, #0097a7);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 188, 212, 0.3);
        }
        
        .temp-btn {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3);
        }
        
        .profile-btn {
            background: linear-gradient(135deg, #4caf50, #388e3c);
            color: white;
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #78909c, #546e7a);
            color: white;
            box-shadow: 0 4px 10px rgba(120, 144, 156, 0.3);
        }
        
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        
        header {
            background: linear-gradient(135deg, var(--nasa-red), var(--nasa-blue));
            color: white;
            border-radius: 12px;
            padding: 1.8rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: 150px;
            opacity: 0.4;
        }

        header h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .top-banner {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .container {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .left-panel {
            flex: 1;
            min-width: 350px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .right-section {
            flex: 2;
            min-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .box {
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .box:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-5px);
        }

        .box h3 {
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Map Container */
        .cyclone-map-container {
            height: 600px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 15px;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Control Panel */
        .control-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .control-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .control-item label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .control-item select,
        .control-item input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .control-item select:focus,
        .control-item input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
            outline: none;
        }

        .btn {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 115, 232, 0.4);
        }

        /* Current Storm Info */
        .storm-info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .storm-card {
            padding: 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .storm-card:hover {
            transform: translateY(-5px);
        }

        .storm-card h4 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .storm-card .storm-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
            position: relative;
            z-index: 2;
        }

        .storm-card .storm-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
            position: relative;
            z-index: 2;
        }

        .storm-detail {
            text-align: center;
            background: rgba(255,255,255,0.15);
            padding: 8px;
            border-radius: 8px;
        }

        .storm-detail .label {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .storm-detail .value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Storm Categories */
        .depression { background: linear-gradient(135deg, #3498db, #2980b9); }
        .deep-depression { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .cyclonic-storm { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .severe-cyclonic-storm { background: linear-gradient(135deg, #8e44ad, #732d91); }
        .very-severe-cyclonic-storm { background: linear-gradient(135deg, #2c3e50, #34495e); }

        /* Legend */
        .legend {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            line-height: 24px;
        }
        
        .legend h4 {
            margin-bottom: 10px;
            color: var(--text-dark);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 5px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid rgba(0,0,0,0.2);
        }

        /* Alerts Panel */
        .alerts-panel {
            max-height: 300px;
            overflow-y: auto;
        }

        .alert-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid;
            background: #f8f9fa;
        }

        .alert-high {
            border-left-color: var(--danger-color);
            background: #fff5f5;
        }

        .alert-medium {
            border-left-color: var(--warning-color);
            background: #fffbf0;
        }

        .alert-low {
            border-left-color: var(--accent-color);
            background: #f0fff4;
        }

        .alert-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .alert-title {
            font-weight: bold;
            color: var(--text-dark);
        }

        .alert-time {
            font-size: 0.8rem;
            color: #666;
        }

        .alert-message {
            color: var(--text-dark);
            line-height: 1.4;
        }

        /* Loading States */
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary-color);
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Satellite Images Section */
        .satellite-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .satellite-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .satellite-header h4 {
            color: var(--satellite-blue);
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .satellite-controls {
            display: flex;
            gap: 8px;
        }

        .satellite-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background: #e3f2fd;
            color: var(--primary-color);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .satellite-btn:hover {
            background: #bbdefb;
        }

        .satellite-btn.active {
            background: var(--satellite-blue);
            color: white;
            box-shadow: 0 2px 8px rgba(26, 115, 232, 0.3);
        }

        .satellite-image-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            height: 280px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .satellite-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
        }

        .satellite-links {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .satellite-link {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: var(--background-light);
            border-radius: 8px;
            color: var(--satellite-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .satellite-link:hover {
            background: var(--satellite-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(26, 115, 232, 0.3);
        }

        /* Status Indicators */
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-active { background-color: #e74c3c; }
        .status-forming { background-color: #f39c12; }
        .status-dissipating { background-color: #3498db; }
        .status-inactive { background-color: #95a5a6; }
        
        /* Forecast section */
        .forecast-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .forecast-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .forecast-tab {
            padding: 10px 15px;
            background: #e3f2fd;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .forecast-tab:hover {
            background: #bbdefb;
        }
        
        .forecast-tab.active {
            background: var(--primary-color);
            color: white;
        }
        
        .forecast-image {
            height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .forecast-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .forecast-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #5f6368;
            margin-top: 10px;
            padding: 0 5px;
        }

        /* API Key Display */
        .api-key-container {
            background: linear-gradient(135deg, #fff8e1, #ffecb3);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        .api-key-display {
            font-size: 0.85rem;
            color: #5f6368;
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            font-family: monospace;
            word-break: break-all;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }

        /* NASA Logo */
        .nasa-logo {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 30px;
            font-weight: bold;
            color: var(--nasa-red);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .nasa-logo i {
            color: var(--nasa-blue);
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

        /* Rainfall Widget Styles */
        .rainfall-widget {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 25px;
        }

        .rainfall-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .rainfall-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rainfall-header h3 {
            font-size: 1.4rem;
            margin: 0;
            color: white;
        }

        .rainfall-header i {
            font-size: 1.5rem;
            color: #64b5f6;
        }

        /* Hourly Rainfall Chart */
        .hourly-chart {
            height: 200px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 8px;
            margin: 20px 0;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .hour-bar {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .bar {
            width: 100%;
            background: linear-gradient(180deg, #42a5f5, #1976d2);
            border-radius: 4px 4px 0 0;
            position: relative;
            transition: all 0.3s ease;
            min-height: 5px;
        }

        .bar:hover {
            background: linear-gradient(180deg, #64b5f6, #1e88e5);
            transform: scaleY(1.1);
        }

        .bar-value {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bar:hover .bar-value {
            opacity: 1;
        }

        .hour-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin-top: 5px;
        }

        .rainfall-value {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64b5f6;
        }

        /* Weekly Forecast */
        .weekly-forecast {
            grid-column: 1 / -1;
        }

        .week-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .day-card {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .day-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        .day-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 10px;
            color: #64b5f6;
        }

        .day-weather {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 10px 0;
        }

        .weather-icon {
            font-size: 1.5rem;
        }

        .temp-range {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .humidity {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .rain-chance {
            font-size: 0.85rem;
            color: #64b5f6;
            font-weight: 500;
        }

        /* Summary Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #64b5f6;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Current Conditions */
        .current-conditions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .condition-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .condition-icon {
            font-size: 1.2rem;
            color: #64b5f6;
            width: 24px;
            text-align: center;
        }

        .condition-text {
            flex: 1;
        }

        .condition-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .condition-value {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Rain Drop Animation */
        @keyframes rainDrop {
            0% { transform: translateY(-10px); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .rain-drop {
            animation: rainDrop 2s ease-in-out infinite;
        }

        .rain-drop:nth-child(2) { animation-delay: 0.5s; }
        .rain-drop:nth-child(3) { animation-delay: 1s; }
        .rain-drop:nth-child(4) { animation-delay: 1.5s; }

        /* Radar Styles */
        .radar-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            z-index: 100;
        }

        .radar-controls {
            display: flex;
            gap: 8px;
        }

        .radar-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .radar-btn:hover {
            background: var(--primary-color);
        }

        .radar-btn.active {
            background: var(--accent-color);
        }

        .radar-info {
            display: flex;
            gap: 15px;
        }

        .radar-legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        /* Radar Container */
        .radar-container {
            position: relative;
            height: 350px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        /* Radar Animation */
        @keyframes radar-sweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .radar-sweep {
            position: absolute;
            top: 0;
            left: 50%;
            width: 100%;
            height: 100%;
            transform-origin: left center;
            background: linear-gradient(90deg, transparent, rgba(0, 200, 255, 0.2));
            clip-path: polygon(50% 50%, 100% 0, 100% 100%);
            animation: radar-sweep 4s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 1100px) {
            .container {
                flex-direction: column;
            }
            
            .left-panel, .right-section {
                width: 100%;
                min-width: unset;
            }
            
            header h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .top-navbar {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }
            
            .welcome-section {
                flex-direction: column;
                text-align: center;
                margin-bottom: 10px;
            }
            
            .user-actions {
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
            }
            
            .nav-btn {
                flex: 1;
                min-width: 140px;
                justify-content: center;
                margin: 5px;
            }
            
            .control-panel {
                grid-template-columns: 1fr;
            }
            
            .cyclone-map-container {
                height: 400px;
            }
            
            .satellite-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .satellite-controls {
                width: 100%;
            }
            
            .satellite-btn {
                flex: 1;
            }
        }

        @media (max-width: 576px) {
            .main-container {
                padding: 15px;
            }
            
            header {
                padding: 1.5rem;
            }
            
            header h1 {
                font-size: 1.8rem;
            }
            
            .box {
                padding: 1.5rem;
            }
            
            .storm-info-cards {
                grid-template-columns: 1fr;
            }
            
            .forecast-tabs {
                flex-direction: column;
            }
            
            .nasa-logo {
                position: relative;
                top: 0;
                right: 0;
                width: fit-content;
                margin: 10px auto;
            }
            
            .radar-overlay {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .radar-controls {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    // Example coordinates (replace with dynamic values if needed)
    $lat = 23.8103;  // Dhaka Latitude
    $lon = 90.4125;  // Dhaka Longitude
    $ventuskyLink = "https://www.ventusky.com/?p={$lat};{$lon};8;";
    ?>
    
    <div class="main-container">
        <!-- NAVBAR AT THE TOP OF THE PAGE -->
        <div class="top-navbar">
            <div class="welcome-section">
                <div class="user-avatar">B</div>
                <div class="welcome-text">
                    <h2>Bangladesh Meteorological Department</h2>
                    <p><i class="fas fa-envelope"></i> met@bmd.gov.bd</p>
                </div>
            </div>
            <div class="user-actions">
                <a href="request.php" class="nav-btn air-quality-btn">
                    <i class="fas fa-wind"></i> Air Quality
                </a>
                <a href="Temperature.php" class="nav-btn temp-btn">
                    <i class="fas fa-thermometer-half"></i> Temperature
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
            <div class="nasa-logo">
                <i class="fas fa-satellite"></i>
                <span>NASA Data Integration</span>
            </div>
            
            <img src="https://images.unsplash.com/photo-1561484930-974554019ade?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Cyclone Tracking Banner" class="top-banner">
            <h1><i class="fas fa-hurricane"></i> Cyclone Tracking System</h1>
            <p>Real-time monitoring with NASA satellite integration</p>
        </header>

        <!-- API Key Section -->
        <div class="api-key-container">
            <h3><i class="fas fa-key"></i> API Key Configuration</h3>
            <p>Your NASA GIBS API key is configured for real-time satellite imagery:</p>
            <div class="api-key-input">
                <div class="api-key-display">API Key: ywc3NkXueHbMQb1cRmzjBeElvtRRodj8Fb5tILc7</div>
            </div>
        </div>

        <!-- Rainfall Data Widgets -->
        <div class="widgets-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 30px;">
            <!-- Hourly Rainfall Widget -->
            <div class="rainfall-widget">
                <div class="rainfall-header">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Hourly Rainfall (Past 6 Hours)</h3>
                </div>
                
                <div class="hourly-chart" id="hourlyChart">
                    <!-- Chart will be generated by JavaScript -->
                </div>
            </div>

            <!-- Current Weather Summary -->
            <div class="rainfall-widget">
                <div class="rainfall-header">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Current Conditions</h3>
                </div>
                
                <div class="current-conditions">
                    <div class="condition-item">
                        <div class="condition-icon">
                            <i class="fas fa-temperature-high"></i>
                        </div>
                        <div class="condition-text">
                            <div class="condition-label">Temperature</div>
                            <div class="condition-value">28°C</div>
                        </div>
                    </div>
                    
                    <div class="condition-item">
                        <div class="condition-icon">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="condition-text">
                            <div class="condition-label">Humidity</div>
                            <div class="condition-value">100%</div>
                        </div>
                    </div>
                    
                    <div class="condition-item">
                        <div class="condition-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="condition-text">
                            <div class="condition-label">Feels Like</div>
                            <div class="condition-value">34°C</div>
                        </div>
                    </div>
                    
                    <div class="condition-item">
                        <div class="condition-icon">
                            <i class="fas fa-smog"></i>
                        </div>
                        <div class="condition-text">
                            <div class="condition-label">Condition</div>
                            <div class="condition-value">Haze</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rainfall Statistics -->
            <div class="rainfall-widget">
                <div class="rainfall-header">
                    <i class="fas fa-calculator"></i>
                    <h3>Rainfall Statistics</h3>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value" id="totalRainfall">13.97mm</div>
                        <div class="stat-label">Total (6hrs)</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value" id="avgRainfall">2.33mm</div>
                        <div class="stat-label">Average/hr</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value" id="maxRainfall">3.74mm</div>
                        <div class="stat-label">Peak Hour</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value" id="rainHours">6/6</div>
                        <div class="stat-label">Rain Hours</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Forecast Widget -->
        <div class="rainfall-widget weekly-forecast">
            <div class="rainfall-header">
                <i class="fas fa-calendar-week"></i>
                <h3>7-Day Weather Forecast</h3>
            </div>
            
            <div class="week-grid" id="weeklyForecast">
                <!-- Weekly forecast will be generated by JavaScript -->
            </div>
        </div>

        <div class="container">
            <div class="left-panel">
                <!-- Control Panel -->
                <div class="box">
                    <h3><i class="fas fa-sliders-h"></i> Tracking Controls</h3>
                    <div class="control-panel">
                        <div class="control-item">
                            <label for="time-range">Time Range</label>
                            <select id="time-range">
                                <option value="24">Last 24 Hours</option>
                                <option value="48">Last 48 Hours</option>
                                <option value="72" selected>Last 72 Hours</option>
                                <option value="168">Last 7 Days</option>
                            </select>
                        </div>
                        <div class="control-item">
                            <label for="storm-type">Storm Type</label>
                            <select id="storm-type">
                                <option value="all" selected>All Storms</option>
                                <option value="depression">Depression</option>
                                <option value="deep-depression">Deep Depression</option>
                                <option value="cyclonic-storm">Cyclonic Storm</option>
                                <option value="severe-cyclonic-storm">Severe Cyclonic Storm</option>
                                <option value="very-severe-cyclonic-storm">Very Severe Cyclonic Storm</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn" id="refresh-data-btn">
                        <i class="fas fa-sync-alt"></i> Refresh Data
                    </button>
                </div>

                <!-- Current Storm Information -->
                <div class="box">
                    <h3><i class="fas fa-info-circle"></i> Active Storms</h3>
                    <div class="storm-info-cards" id="storm-info-cards">
                        <!-- Content will be populated by JS -->
                    </div>
                </div>

                <!-- Weather Alerts -->
                <div class="box">
                    <h3><i class="fas fa-exclamation-triangle"></i> Weather Alerts</h3>
                    <div class="alerts-panel" id="alerts-panel">
                        <!-- Content will be populated by JS -->
                    </div>
                </div>
            </div>

            <div class="right-section">
                <!-- Main Map -->
                <div class="box">
                    <h3><i class="fas fa-map"></i> Real-time Cyclone Tracking</h3>
                    <div class="cyclone-map-container" id="cyclone-map"></div>
                </div>
                
                <!-- Weather Map -->
                <div class="box">
                    <h3><i class="fas fa-globe"></i> Weather Map</h3>
                    <div class="cyclone-map-container">
                        <iframe 
                            src="<?php echo $ventuskyLink; ?>" 
                            style="width: 100%; height: 100%; border: none;" 
                            loading="lazy"
                            title="Ventusky Weather Map"
                            onerror="console.error('Failed to load Ventusky map.')"
                        ></iframe>
                    </div>
                </div>
                
                <div class="container" style="flex-direction: row; gap: 2rem; margin-top: -1rem;">
                    <!-- NASA Satellite Images -->
                    <div class="box" style="flex: 1;">
                        <h3><i class="fas fa-satellite"></i> NASA Satellite Images</h3>
                        <div class="satellite-container">
                            <div class="satellite-header">
                                <h4>Latest Bay of Bengal View</h4>
                                <div class="satellite-controls">
                                    <button class="satellite-btn active" data-type="ir">Infrared</button>
                                    <button class="satellite-btn" data-type="vis">Visible</button>
                                    <button class="satellite-btn" data-type="wv">Water Vapor</button>
                                </div>
                            </div>
                            <div class="satellite-image-container">
                                <img src="" 
                                    alt="NASA Satellite Image" 
                                    class="satellite-image"
                                    id="satellite-image">
                                <div class="image-overlay">
                                    <span id="satellite-caption">Loading NASA image...</span>
                                    <span id="satellite-timestamp">2024-06-26 12:00 UTC</span>
                                </div>
                            </div>
                            <div class="satellite-links">
                                <a href="https://earthdata.nasa.gov/gibs" target="_blank" class="satellite-link">
                                    <i class="fas fa-external-link-alt"></i> NASA GIBS Portal
                                </a>
                                <a href="#" class="satellite-link" id="download-image">
                                    <i class="fas fa-download"></i> Download Image
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Forecast Models -->
                    <div class="box" style="flex: 1;">
                        <h3><i class="fas fa-chart-line"></i> NASA Forecast Models</h3>
                        <div class="forecast-container">
                            <div class="forecast-tabs">
                                <button class="forecast-tab active" data-model="gmao">GMAO</button>
                                <button class="forecast-tab" data-model="geos">GEOS</button>
                                <button class="forecast-tab" data-model="merra">MERRA-2</button>
                            </div>
                            <div class="forecast-content">
                                <div class="forecast-image">
                                    <img src="" 
                                        alt="NASA Forecast Model" 
                                        id="forecast-img">
                                </div>
                                <div class="forecast-info">
                                    <p>Model: <span id="model-name">GMAO Surface Wind</span></p>
                                    <p>Date: <span id="model-date">2024-06-26</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Leaflet JS for maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Global variables
        let map;
        let stormMarkers = [];
        const NASA_API_KEY = 'ywc3NkXueHbMQb1cRmzjBeElvtRRodj8Fb5tILc7';
        const BASE_GIBS_URL = 'https://gibs.earthdata.nasa.gov';

        // Sample storm data for demonstration
        const sampleStorms = [
            {
                name: "Cyclone Mocha",
                type: "very-severe-cyclonic-storm",
                lat: 19.5,
                lng: 91.2,
                windSpeed: 185,
                pressure: 950,
                movement: "NNE at 15 km/h",
                status: "active",
                category: "VSCS"
            },
            {
                name: "Depression BOB-02",
                type: "depression",
                lat: 16.8,
                lng: 88.5,
                windSpeed: 55,
                pressure: 996,
                movement: "NW at 8 km/h",
                status: "forming",
                category: "D"
            }
        ];

        // Sample alerts data
        const sampleAlerts = [
            {
                level: "high",
                title: "Cyclone Warning",
                message: "Very Severe Cyclonic Storm 'Mocha' expected to make landfall near Cox's Bazar within 24 hours. Coastal areas should evacuate immediately.",
                time: "2 hours ago"
            },
            {
                level: "medium",
                title: "Storm Surge Alert",
                message: "Coastal areas of Chittagong may experience storm surge of 3-4 meters. Fishermen advised not to venture into the sea.",
                time: "4 hours ago"
            },
            {
                level: "low",
                title: "Weather Update",
                message: "Depression in Bay of Bengal likely to intensify into cyclonic storm. Residents advised to prepare emergency kits.",
                time: "6 hours ago"
            }
        ];

        // Satellite images data - now including API key
        const satelliteImages = {
            ir: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=MODIS_Terra_CorrectedReflectance_TrueColor&opacities=1&worldfile=false&format=image/jpeg&width=1800&height=1080&api_key=${NASA_API_KEY}`,
                caption: "NASA GIBS - MODIS Terra Infrared",
                timestamp: "2024-06-26 12:00 UTC"
            },
            vis: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=MODIS_Aqua_CorrectedReflectance_TrueColor&opacities=1&worldfile=false&format=image/jpeg&width=1800&height=1080&api_key=${NASA_API_KEY}`,
                caption: "NASA GIBS - MODIS Aqua Visible",
                timestamp: "2024-06-26 12:00 UTC"
            },
            wv: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=VIIRS_SNPP_CorrectedReflectance_TrueColor&opacities=1&worldfile=false&format=image/jpeg&width=1800&height=1080&api_key=${NASA_API_KEY}`,
                caption: "NASA GIBS - VIIRS Water Vapor",
                timestamp: "2024-06-26 12:00 UTC"
            }
        };

        // Forecast model images - now including API key
        const forecastModels = {
            gmao: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=GMAO_Surface_Wind_Speed&opacities=1&worldfile=false&format=image/png&width=800&height=600&api_key=${NASA_API_KEY}`,
                name: "GMAO Surface Wind"
            },
            geos: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=GEOS_Surface_Wind&opacities=1&worldfile=false&format=image/png&width=800&height=600&api_key=${NASA_API_KEY}`,
                name: "GEOS Surface Wind"
            },
            merra: {
                url: `${BASE_GIBS_URL}/image-download?TIME=20240626&extent=80,10,100,25&epsg=4326&layers=MERRA2_Surface_Wind&opacities=1&worldfile=false&format=image/png&width=800&height=600&api_key=${NASA_API_KEY}`,
                name: "MERRA-2 Surface Wind"
            }
        };

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            loadStormData();
            loadWeatherAlerts();
            
            // Set initial images
            updateSatelliteImage('ir');
            updateForecastModel('gmao');
            
            // Initialize rainfall widgets
            generateHourlyChart();
            generateWeeklyForecast();
            calculateRainfallStatistics();
            updateRainfallConditions();
            
            // Event listeners
            document.getElementById('refresh-data-btn').addEventListener('click', refreshAllData);
            document.getElementById('time-range').addEventListener('change', updateMapData);
            document.getElementById('storm-type').addEventListener('change', updateMapData);
            
            // Satellite image switching
            document.querySelectorAll('.satellite-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.satellite-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Update satellite image
                    const type = this.getAttribute('data-type');
                    updateSatelliteImage(type);
                });
            });
            
            // Forecast model switching
            document.querySelectorAll('.forecast-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    document.querySelectorAll('.forecast-tab').forEach(t => {
                        t.classList.remove('active');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Update forecast model
                    const model = this.getAttribute('data-model');
                    updateForecastModel(model);
                });
            });
            
            // Download image button
            document.getElementById('download-image').addEventListener('click', function(e) {
                e.preventDefault();
                const imgUrl = document.getElementById('satellite-image').src;
                const link = document.createElement('a');
                link.href = imgUrl;
                link.download = 'bmd_satellite_image.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
            
            // Fullscreen button for radar
            document.getElementById('fullscreen-btn').addEventListener('click', function() {
                const radarContainer = document.querySelector('.radar-container');
                if (radarContainer.requestFullscreen) {
                    radarContainer.requestFullscreen();
                } else if (radarContainer.webkitRequestFullscreen) {
                    radarContainer.webkitRequestFullscreen();
                } else if (radarContainer.msRequestFullscreen) {
                    radarContainer.msRequestFullscreen();
                }
            });
            
            // Add interactive effects for rainfall widgets
            const rainfallWidgets = document.querySelectorAll('.rainfall-widget');
            
            rainfallWidgets.forEach(widget => {
                widget.addEventListener('mouseenter', function() {
                    this.style.background = 'rgba(255, 255, 255, 0.2)';
                });
                
                widget.addEventListener('mouseleave', function() {
                    this.style.background = 'rgba(255, 255, 255, 0.15)';
                });
            });
        });

        // Initialize the Leaflet map
        function initializeMap() {
            // Create map centered on Bay of Bengal
            map = L.map('cyclone-map').setView([20.0, 88.0], 6);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add NASA satellite layer
            addNASASatelliteLayer();

            // Add legend
            const legend = L.control({position: 'bottomright'});
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'legend');
                div.innerHTML = `
                    <h4><i class="fas fa-hurricane"></i> Storm Categories</h4>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #3498db;"></div>
                        <span>Depression (D)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f39c12;"></div>
                        <span>Deep Depression (DD)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #e74c3c;"></div>
                        <span>Cyclonic Storm (CS)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #8e44ad;"></div>
                        <span>Severe Cyclonic Storm (SCS)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #2c3e50;"></div>
                        <span>Very Severe Cyclonic Storm (VSCS)</span>
                    </div>
                `;
                return div;
            };
            legend.addTo(map);
            
            // Add storm tracks
            addStormTracks();
        }

        // Add NASA Satellite Layer
        function addNASASatelliteLayer() {
            // Add NASA GIBS layer (MODIS True Color)
            const satelliteLayer = L.tileLayer('https://gibs.earthdata.nasa.gov/wmts/epsg3857/best/MODIS_Terra_CorrectedReflectance_TrueColor/default/{time}/{tileMatrixSet}/{z}/{y}/{x}.jpg', {
                attribution: 'Imagery provided by NASA GIBS',
                bounds: [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]],
                minZoom: 1,
                maxZoom: 9,
                tileSize: 256,
                subdomains: 'abc',
                noWrap: true,
                time: new Date().toISOString().split('T')[0],
                tileMatrixSet: 'GoogleMapsCompatible_Level9',
                format: 'image/jpeg',
                opacity: 0.7
            }).addTo(map);
        }

        // Add storm tracks to the map
        function addStormTracks() {
            // Sample storm tracks
            const mochaTrack = [
                [12.5, 85.0], [13.8, 86.2], [15.5, 87.5], [17.2, 88.8], [18.8, 90.0], [19.5, 91.2]
            ];
            
            const bobTrack = [
                [14.0, 86.5], [15.2, 87.0], [16.0, 87.5], [16.8, 88.5]
            ];
            
            // Create polyline for each track
            const mochaLine = L.polyline(mochaTrack, {color: '#8e44ad', weight: 3}).addTo(map);
            const bobLine = L.polyline(bobTrack, {color: '#3498db', weight: 3}).addTo(map);
            
            // Add markers at each point
            mochaTrack.forEach(point => {
                L.circleMarker(point, {radius: 3, color: '#8e44ad', fillOpacity: 1}).addTo(map);
            });
            
            bobTrack.forEach(point => {
                L.circleMarker(point, {radius: 3, color: '#3498db', fillOpacity: 1}).addTo(map);
            });
            
            // Add start and end markers
            L.circleMarker(mochaTrack[0], {radius: 5, color: '#fff', fillColor: '#8e44ad', fillOpacity: 1}).addTo(map)
                .bindPopup('Mocha Start: 12.5°N, 85.0°E');
            
            L.circleMarker(mochaTrack[mochaTrack.length-1], {radius: 8, color: '#fff', fillColor: '#8e44ad', fillOpacity: 1}).addTo(map)
                .bindPopup('Mocha Current: 19.5°N, 91.2°E');
        }

        // Load storm data
        function loadStormData() {
            // In a real application, this would fetch from API
            stormsData = sampleStorms;
            updateStormCards();
            updateMapMarkers();
        }

        // Load weather alerts
        function loadWeatherAlerts() {
            // In a real application, this would fetch from API
            alertsData = sampleAlerts;
            updateAlertsPanel();
        }

        // Update storm information cards
        function updateStormCards() {
            const container = document.getElementById('storm-info-cards');
            
            if (stormsData.length === 0) {
                container.innerHTML = '<div class="loading"><p>No active storms detected</p></div>';
                return;
            }

            container.innerHTML = stormsData.map(storm => `
                <div class="storm-card ${storm.type}">
                    <h4>
                        <i class="fas fa-hurricane"></i>
                        <span class="status-indicator status-${storm.status}"></span>
                        ${storm.category}
                    </h4>
                    <div class="storm-name">${storm.name}</div>
                    <div class="storm-details">
                        <div class="storm-detail">
                            <div class="label">Wind Speed</div>
                            <div class="value">${storm.windSpeed} km/h</div>
                        </div>
                        <div class="storm-detail">
                            <div class="label">Pressure</div>
                            <div class="value">${storm.pressure} hPa</div>
                        </div>
                        <div class="storm-detail">
                            <div class="label">Movement</div>
                            <div class="value">${storm.movement}</div>
                        </div>
                        <div class="storm-detail">
                            <div class="label">Status</div>
                            <div class="value">${storm.status.charAt(0).toUpperCase() + storm.status.slice(1)}</div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update map markers
        function updateMapMarkers() {
            // Clear existing markers
            stormMarkers.forEach(marker => map.removeLayer(marker));
            stormMarkers = [];
            
            // Add new markers
            stormsData.forEach(storm => {
                // Get marker color based on storm type
                let markerColor;
                switch(storm.type) {
                    case 'depression': markerColor = '#3498db'; break;
                    case 'deep-depression': markerColor = '#f39c12'; break;
                    case 'cyclonic-storm': markerColor = '#e74c3c'; break;
                    case 'severe-cyclonic-storm': markerColor = '#8e44ad'; break;
                    case 'very-severe-cyclonic-storm': markerColor = '#2c3e50'; break;
                    default: markerColor = '#95a5a6';
                }
                
                // Create custom marker
                const marker = L.marker([storm.lat, storm.lng], {
                    title: storm.name,
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color:${markerColor}; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);">${storm.category}</div>`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(map);
                
                // Add popup
                marker.bindPopup(`
                    <div style="min-width: 200px">
                        <h4 style="margin: 5px 0; text-align: center;">
                            <i class="fas fa-hurricane"></i> ${storm.name}
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; margin-top: 10px;">
                            <div><strong>Type:</strong></div>
                            <div>${storm.type.replace(/-/g, ' ')}</div>
                            <div><strong>Category:</strong></div>
                            <div>${storm.category}</div>
                            <div><strong>Wind Speed:</strong></div>
                            <div>${storm.windSpeed} km/h</div>
                            <div><strong>Pressure:</strong></div>
                            <div>${storm.pressure} hPa</div>
                            <div><strong>Movement:</strong></div>
                            <div>${storm.movement}</div>
                        </div>
                    </div>
                `);
                
                stormMarkers.push(marker);
            });
        }

        // Update weather alerts panel
        function updateAlertsPanel() {
            const container = document.getElementById('alerts-panel');
            
            if (alertsData.length === 0) {
                container.innerHTML = '<div class="loading"><p>No active alerts</p></div>';
                return;
            }
            
            container.innerHTML = alertsData.map(alert => `
                <div class="alert-item alert-${alert.level}">
                    <div class="alert-header">
                        <div class="alert-title">${alert.title}</div>
                        <div class="alert-time">${alert.time}</div>
                    </div>
                    <div class="alert-message">${alert.message}</div>
                </div>
            `).join('');
        }

        // Refresh all data
        function refreshAllData() {
            const btn = document.getElementById('refresh-data-btn');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                loadStormData();
                loadWeatherAlerts();
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1500);
        }

        // Update map data based on filters
        function updateMapData() {
            const timeRange = document.getElementById('time-range').value;
            const stormType = document.getElementById('storm-type').value;
            
            // In a real app, this would filter data from an API
            loadStormData();
        }
        
        // Update satellite image
        function updateSatelliteImage(type) {
            const image = document.getElementById('satellite-image');
            const caption = document.getElementById('satellite-caption');
            const timestamp = document.getElementById('satellite-timestamp');
            
            const imageData = satelliteImages[type];
            
            image.src = imageData.url;
            caption.textContent = imageData.caption;
            timestamp.textContent = imageData.timestamp;
        }
        
        // Update forecast model
        function updateForecastModel(model) {
            const image = document.getElementById('forecast-img');
            const modelData = forecastModels[model];
            
            // Append API key to URL
            const urlWithKey = `${modelData.url}&api_key=${NASA_API_KEY}`;
            
            image.src = urlWithKey;
        }

        // Rainfall Widget Functions
        // Hourly rainfall data
        const hourlyData = [
            { hour: '12am', rainfall: 0.23 },
            { hour: '1am', rainfall: 2.66 },
            { hour: '2am', rainfall: 2.93 },
            { hour: '3am', rainfall: 3.44 },
            { hour: '4am', rainfall: 3.74 },
            { hour: '5am', rainfall: 1.97 }
        ];

        // Weekly forecast data
        const weeklyData = [
            { day: 'Today', temp: '32°/27°', humidity: '100%', icon: 'fas fa-cloud-rain', condition: 'Thunderstorms' },
            { day: 'Fri', temp: '31°/26°', humidity: '59%', icon: 'fas fa-cloud-rain', condition: 'Rain' },
            { day: 'Sat', temp: '31°/26°', humidity: '66%', icon: 'fas fa-cloud-rain', condition: 'Rain' },
            { day: 'Sun', temp: '31°/27°', humidity: '60%', icon: 'fas fa-cloud-rain', condition: 'Rain' },
            { day: 'Mon', temp: '31°/26°', humidity: '81%', icon: 'fas fa-cloud-rain', condition: 'Thunderstorms' },
            { day: 'Tue', temp: '31°/26°', humidity: '88%', icon: 'fas fa-cloud-rain', condition: 'Rain' },
            { day: 'Wed', temp: '30°/26°', humidity: '80%', icon: 'fas fa-cloud-rain', condition: 'Rain' }
        ];

        function generateHourlyChart() {
            const chartContainer = document.getElementById('hourlyChart');
            const maxRainfall = Math.max(...hourlyData.map(d => d.rainfall));
            
            chartContainer.innerHTML = '';
            
            hourlyData.forEach(data => {
                const barHeight = (data.rainfall / maxRainfall) * 150; // 150px max height
                
                const hourBar = document.createElement('div');
                hourBar.className = 'hour-bar';
                
                hourBar.innerHTML = `
                    <div class="bar" style="height: ${barHeight}px">
                        <div class="bar-value">${data.rainfall}mm</div>
                    </div>
                    <div class="hour-label">${data.hour}</div>
                    <div class="rainfall-value">${data.rainfall}mm</div>
                `;
                
                chartContainer.appendChild(hourBar);
            });
        }

        function generateWeeklyForecast() {
            const forecastContainer = document.getElementById('weeklyForecast');
            
            forecastContainer.innerHTML = '';
            
            weeklyData.forEach(data => {
                const dayCard = document.createElement('div');
                dayCard.className = 'day-card';
                
                dayCard.innerHTML = `
                    <div class="day-name">${data.day}</div>
                    <div class="day-weather">
                        <i class="${data.icon} weather-icon"></i>
                    </div>
                    <div class="temp-range">${data.temp}</div>
                    <div class="humidity">
                        <i class="fas fa-tint"></i>
                        <span>${data.humidity}</span>
                    </div>
                    <div class="rain-chance">${data.condition}</div>
                `;
                
                forecastContainer.appendChild(dayCard);
            });
        }

        function calculateRainfallStatistics() {
            const total = hourlyData.reduce((sum, data) => sum + data.rainfall, 0);
            const average = total / hourlyData.length;
            const max = Math.max(...hourlyData.map(d => d.rainfall));
            const rainHours = hourlyData.filter(d => d.rainfall > 0).length;
            
            document.getElementById('totalRainfall').textContent = total.toFixed(2) + 'mm';
            document.getElementById('avgRainfall').textContent = average.toFixed(2) + 'mm';
            document.getElementById('maxRainfall').textContent = max.toFixed(2) + 'mm';
            document.getElementById('rainHours').textContent = `${rainHours}/${hourlyData.length}`;
        }

        function updateRainfallConditions() {
            // Simulate real-time updates for current conditions
            const conditions = document.querySelectorAll('.condition-value');
            
            setInterval(() => {
                // Slight variations for demonstration
                const temp = 28 + (Math.random() - 0.5);
                const humidity = Math.min(100, 95 + Math.random() * 10);
                const feelsLike = temp + 6 + (Math.random() - 0.5);
                
                conditions[0].textContent = temp.toFixed(1) + '°C';
                conditions[1].textContent = humidity.toFixed(0) + '%';
                conditions[2].textContent = feelsLike.toFixed(1) + '°C';
            }, 30000); // Update every 30 seconds
        }
    </script>
</body>
</html>