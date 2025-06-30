
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard Monitoring IoT')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color:rgb(255, 255, 255);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color:rgb(31, 84, 128);
            font-size: 24px;
        }

        .logo-text {
            font-weight: bold;
            font-size: 18px;
        }

        .menu-item-link, .submenu-item-link {
    color: inherit;
    text-decoration: none !important;
    display: block;
}

/* Menu Item */
.menu-item {
    padding: 10px 20px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.3s;
    color: white;
}

.menu-item i {
    margin-right: 10px;
    font-size: 14px;
}

.menu-item:hover, .menu-item.active {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Submenu */
.submenu {
    padding-left: 20px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    list-style: none;
}

.submenu.open {
    max-height: 200px;
}

.submenu-item {
    padding: 8px 12px;
    margin: 4px 0;
    cursor: pointer;
    color: white;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.submenu-item:hover, .submenu-item.active {
    background-color: rgba(255, 255, 255, 0.1);
}


        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            color: #5a5c69;
        }

        .user-info {
    position: relative;
    display: inline-block;
    cursor: pointer;
    padding: 8px 12px;
}

.user-name {
    margin-right: 10px;
    font-weight: bold;
}

.user-avatar {
    display: inline-block;
    width: 32px;
    height: 32px;
    background-color:rgb(31, 84, 128);
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 32px;
    font-weight: bold;
}

.user-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    min-width: 150px;
    z-index: 1000;
    margin-top: 10px;
}

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid transparent;
        }

        .card.blue {
            border-left-color: #4e73df;
        }

        .card.orange {
            border-left-color: #f6c23e;
        }

        .card.teal {
            border-left-color: #36b9cc;
        }

        .card.green {
            border-left-color: #1cc88a;
        }

        .card-info h5 {
            font-size: 12px;
            color: #b7b9cc;
            margin-bottom: 5px;
        }

        .card-info h2 {
            font-size: 24px;
            color: #5a5c69;
        }

        .card-icon {
            font-size: 24px;
            opacity: 0.3;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e3e6f0;
        }

        .chart-title {
            font-size: 16px;
            color:rgb(31, 84, 128);
            font-weight: bold;
        }

        .chart-actions {
            color: #dddfeb;
            cursor: pointer;
        }

        .chart-content {
            padding: 20px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .generate-btn {
            background-color:rgb(29, 48, 82);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .generate-btn:hover {
            background-color:rgb(29, 48, 82);
        }
        
        /* For the progress bar in humidity */
        .progress-bar {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 20px;
            height: 10px;
            margin-top: 10px;
        }
        
        .progress-value {
            height: 100%;
            background-color: #36b9cc;
            border-radius: 20px;
            width: 50%; /* Initial value */
        }
        
        .thingspeak-settings {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .settings-row {
            display: flex;
            gap: 15px;
        }
        
        .settings-column {
            flex: 1;
        }
        
        .last-update {
            text-align: right;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-online {
            background-color: #1cc88a;
        }
        
        .status-offline {
            background-color: #e74a3b;
        }
        
        /* User dropdown menu */
        .user-dropdown {
    display: none;
    position: absolute;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    right: 0;
    top: 100%;
    min-width: 150px;
    z-index: 1000;
}

.user-dropdown.active {
    display: block;
}

.dropdown-item {
    padding: 10px 15px;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f0f0f0;
}

.dropdown-divider {
    border-bottom: 1px solid #ddd;
    margin: 5px 0;
}

        
        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .modal.active .modal-content {
            transform: translateY(0);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 20px;
            color: #4e73df;
            font-weight: bold;
        }
        
        .modal-close {
            cursor: pointer;
            font-size: 24px;
            color: #ccc;
        }
        
        .modal-close:hover {
            color: #666;
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: bold;
        }
        
        .btn-primary {
            background-color:rgb(29, 48, 82);
            color: white;
        }
        
        .btn-primary:hover {
            background-color:rgb(29, 48, 82);
        }
        
        .btn-secondary {
            background-color: #858796;
            color: white;
            margin-right: 10px;
        }
        
        .btn-secondary:hover {
            background-color: #717384;
        }
        
        /* Form validation */
        .form-error {
            color: #e74a3b;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .input-error {
            border-color: #e74a3b !important;
        }
        
        /* Responsive design */
    </style>
</head>
<body>
        <div class="main-content">
            <div class="header">
                <h1 class="page-title">Dashboard</h1>
                <!-- Profile Dropdown -->
<div class="user-info" id="userInfo">
    <span class="user-name" id="displayUsername">{{ Auth::user()->name ?? 'User' }}</span>
    <div class="user-avatar" id="userAvatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>

    <!-- Dropdown -->
    <div class="user-dropdown" id="userDropdown">
        <div class="dropdown-item" onclick="openProfileModal()">
            👤 Profile
        </div>
        <div class="dropdown-divider"></div>
        <div class="dropdown-item" onclick="logout(event)">
            🚪 Logout
        </div>
    </div>
</div>

            </div>
            
            <div class="thingspeak-settings">
                <h3>ThingSpeak Connection</h3>
                <div class="last-update">
                    Connection Status: <span id="connectionStatus"><span class="status-indicator status-offline"></span>Not Connected</span> | 
                    Last Updated: <span id="lastUpdated">Never</span>
                </div>
            </div>
            
            <div style="text-align: right; margin-bottom: 20px;">
                <button class="generate-btn" id="generateReportBtn">
                    <i>📄</i> Generate Report
                </button>
            </div>
            
            <div class="cards-container">
                <div class="card blue">
                    <div class="card-info">
                        <h5>TOTAL DATA</h5>
                        <h2 id="totalData">0</h2>
                    </div>
                    <div class="card-icon">💾</div>
                </div>
                
                <div class="card orange">
                    <div class="card-info">
                        <h5>AVERAGE TEMPERATURE</h5>
                        <h2 id="avgTemp">0.0</h2>
                    </div>
                    <div class="card-icon">🌡️</div>
                </div>
                
                <div class="card teal">
                    <div class="card-info">
                        <h5>AVERAGE HUMIDITY</h5>
                        <h2 id="avgHumidity">0%</h2>
                        <div class="progress-bar">
                            <div class="progress-value" id="humidityProgress"></div>
                        </div>
                    </div>
                    <div class="card-icon">💧</div>
                </div>
                
                <div class="card green">
                    <div class="card-info">
                        <h5>DEVICE ONLINE</h5>
                        <h2 id="deviceOnline">0</h2>
                    </div>
                    <div class="card-icon">📱</div>
                </div>
            </div>
            
            <div class="charts-container">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Suhu</div>
                        <div class="chart-actions">⋮</div>
                    </div>
                    <div class="chart-content" id="suhuChart">
                        <canvas id="temperatureChart" width="400" height="250"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Kelembaban</div>
                        <div class="chart-actions">⋮</div>
                    </div>
                    <div class="chart-content" id="kelembabbanChart">
                        <canvas id="humidityChart" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="charts-container">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">pH Air</div>
                        <div class="chart-actions">⋮</div>
                    </div>
                    <div class="chart-content" id="phChart">
                        <canvas id="phWaterChart" width="400" height="250"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Gas</div>
                        <div class="chart-actions">⋮</div>
                    </div>
                    <div class="chart-content" id="gasChart">
                        <canvas id="gasLevelChart" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Modal -->
    <div class="modal" id="profileModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">User Profile</div>
                <div class="modal-close" onclick="closeModal('profileModal')"></div>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; background-color: #4e73df; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 36px; margin-bottom: 10px;" id="profileAvatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <h3 id="profileName">{{ Auth::user()->name ?? 'User' }}</h3>
                    <p id="profileEmail">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('profileModal')">Close</button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Toggle submenu
        const userInfo = document.getElementById('userInfo');
if (userInfo) {
    userInfo.addEventListener('click', function(e) {
        e.stopPropagation();
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.classList.toggle('active');
        }
    });
}
        
        // Toggle user dropdown
        document.getElementById('userInfo').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
        });
        
        // Close dropdown when clicking elsewhere
        document.addEventListener('click', function() {
            document.getElementById('userDropdown').classList.remove('active');
        });
        
        // Chart objects
        let temperatureChart, humidityChart, phChart, gasChart;
        
        // ThingSpeak data
        let thingSpeakData = {
            channelId: '2954806',
            readApiKey: 'SFWJ5UAULE9O6S0L',
            tempField: 1,
            humidityField: 2,
            phField: 3,
            gasField: 4,
            updateInterval: 10, // seconds
            resultsCount: 40,
            connected: false,
            updateTimer: null,
            lastUpdate: null,
            totalDataPoints: 0,
            deviceStatus: 0
        };
        
        // User management
        let userSystem = {
            currentUser: null,
            users: [], // This would be stored in a database in a real application
            
            // Check if user is logged in
            isLoggedIn() {
                return this.currentUser !== null;
            },
            
            // Logout current user
            logout() {
                this.currentUser = null;
                sessionStorage.removeItem('currentUser');
                // In a real app, you would redirect to login page
                window.location.reload();
            }
        };
        
        // Initialize charts
        function initializeCharts() {
            // Temperature Chart
            const tempCtx = document.getElementById('temperatureChart').getContext('2d');
            temperatureChart = new Chart(tempCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Temperature (°C)',
                        data: [],
                        borderColor: '#f6c23e',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Temperature (°C)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time'
                        }
                    }
                }
            }
            });
            
            // Humidity Chart
            const humidityCtx = document.getElementById('humidityChart').getContext('2d');
            humidityChart = new Chart(humidityCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Humidity (%)',
                        data: [],
                        borderColor: '#36b9cc',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Humidity (%)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time'
                            }
                        }
                    }
                }
            });
            
            // pH Chart
            const phCtx = document.getElementById('phWaterChart').getContext('2d');
            phChart = new Chart(phCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'pH Level',
                        data: [],
                        borderColor: '#e74a3b',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 0,
                            max: 14,
                            title: {
                                display: true,
                                text: 'pH Level'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time'
                            }
                        }
                    }
                }
            });
            
            // Gas Chart
            const gasCtx = document.getElementById('gasLevelChart').getContext('2d');
            gasChart = new Chart(gasCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Gas Level (ppm)',
                        data: [],
                        borderColor: '#1cc88a',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Gas Level (ppm)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time'
                            }
                        }
                    }
                }
            });
        }
        
        // Functions to fetch and update ThingSpeak data
        function connectToThingSpeak() {
            // Set as connected
            thingSpeakData.connected = true;
            
            // Update UI
            updateConnectionStatus();
            
            // Fetch data immediately
            fetchThingSpeakData();
            
            // Set up timer for regular updates
            thingSpeakData.updateTimer = setInterval(fetchThingSpeakData, thingSpeakData.updateInterval * 1000);
        }
        
        function fetchThingSpeakData() {
            if (!thingSpeakData.connected) return;
            
            // Construct the API URL
            let apiUrl = `https://api.thingspeak.com/channels/${thingSpeakData.channelId}/feeds.json`;
            
            // Add parameters
            apiUrl += `?results=${thingSpeakData.resultsCount}`;
            if (thingSpeakData.readApiKey) {
                apiUrl += `&api_key=${thingSpeakData.readApiKey}`;
            }
            
            // Fetch data from ThingSpeak
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    processThingSpeakData(data);
                })
                .catch(error => {
                    console.error('Error fetching ThingSpeak data:', error);
                    // Update connection status as offline
                    thingSpeakData.connected = false;
                    updateConnectionStatus();
                });
        }
        
        function processThingSpeakData(data) {
            if (!data || !data.feeds || data.feeds.length === 0) {
                console.warn('No data received from ThingSpeak');
                return;
            }
            
            // Update last update time
            thingSpeakData.lastUpdate = new Date();
            updateConnectionStatus();
            
            // Update total data count
            thingSpeakData.totalDataPoints = data.feeds.length;
            document.getElementById('totalData').textContent = thingSpeakData.totalDataPoints;
            
            // Set device status (assuming if we have data, device is online)
            thingSpeakData.deviceStatus = 1;
            document.getElementById('deviceOnline').textContent = thingSpeakData.deviceStatus;
            
            // Prepare data for charts
            const labels = [];
            const tempData = [];
            const humidityData = [];
            const phData = [];
            const gasData = [];
            
            // Process each data point
            data.feeds.forEach(feed => {
                // Format the date for display
                const dateObj = new Date(feed.created_at);
                const timeLabel = dateObj.toLocaleTimeString();
                labels.push(timeLabel);
                
                // Extract field values (using field number from settings)
                const temp = parseFloat(feed[`field${thingSpeakData.tempField}`]);
                const humidity = parseFloat(feed[`field${thingSpeakData.humidityField}`]);
                const ph = parseFloat(feed[`field${thingSpeakData.phField}`]);
                const gas = parseFloat(feed[`field${thingSpeakData.gasField}`]);
                
                // Add to data arrays (handling null/undefined values)
                tempData.push(isNaN(temp) ? null : temp);
                humidityData.push(isNaN(humidity) ? null : humidity);
                phData.push(isNaN(ph) ? null : ph);
                gasData.push(isNaN(gas) ? null : gas);
            });
            
            // Calculate averages
            const avgTemp = calculateAverage(tempData);
            const avgHumidity = calculateAverage(humidityData);
            
            // Update summary statistics
            document.getElementById('avgTemp').textContent = avgTemp.toFixed(1) + '°C';
            document.getElementById('avgHumidity').textContent = avgHumidity.toFixed(0) + '%';
            
            // Update humidity progress bar
            const humidityProgressElement = document.getElementById('humidityProgress');
            humidityProgressElement.style.width = `${avgHumidity}%`;
            
            // Update charts
            updateChart(temperatureChart, labels, tempData);
            updateChart(humidityChart, labels, humidityData);
            updateChart(phChart, labels, phData);
            updateChart(gasChart, labels, gasData);
        }
        
        function updateChart(chart, labels, data) {
            if (!chart) return;
            
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update();
        }
        
        function calculateAverage(arr) {
            const validValues = arr.filter(val => val !== null && !isNaN(val));
            if (validValues.length === 0) return 0;
            
            const sum = validValues.reduce((a, b) => a + b, 0);
            return sum / validValues.length;
        }
        
        function updateConnectionStatus() {
            const statusElem = document.getElementById('connectionStatus');
            const lastUpdatedElem = document.getElementById('lastUpdated');
            
            if (thingSpeakData.connected) {
                statusElem.innerHTML = '<span class="status-indicator status-online"></span>Connected';
                
                if (thingSpeakData.lastUpdate) {
                    lastUpdatedElem.textContent = thingSpeakData.lastUpdate.toLocaleTimeString();
                } else {
                    lastUpdatedElem.textContent = 'Waiting for data...';
                }
            } else {
                statusElem.innerHTML = '<span class="status-indicator status-offline"></span>Not Connected';
                lastUpdatedElem.textContent = 'Never';
            }
        }
        
        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
        
        function openProfileModal() {
            document.getElementById('userDropdown').classList.remove('active');
            openModal('profileModal');
        }
        
        function logout() {
            userSystem.logout();
        }
        
        // Initialize the application
        function initializeApp() {
            // Initialize charts
            initializeCharts();
            
            // Connect to ThingSpeak
            connectToThingSpeak();
            
            // Set up event listeners for report generation
            document.getElementById('generateReportBtn').addEventListener('click', function() {
                if (!thingSpeakData.connected) {
                    alert('Please connect to ThingSpeak first to generate a report.');
                    return;
                }
                
                // Simple implementation - could be expanded to generate proper reports
                
            });
        }
        
        
        // Run initialization when DOM content is loaded
        
    </script>  
</script>
 <script>
    document.addEventListener('DOMContentLoaded', initializeApp);
    document.addEventListener('DOMContentLoaded', function () {
        const userInfo = document.getElementById('userInfo');
        const userDropdown = document.getElementById('userDropdown');

        if (userInfo) {
            userInfo.addEventListener('click', function (e) {
                e.stopPropagation();
                if (userDropdown) {
                    userDropdown.classList.toggle('active');
                }
            });
        }

        // Klik di luar dropdown akan menutupnya
        document.addEventListener('click', function () {
            if (userDropdown && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
            }
        });
    });

   

    function logout(event) {
        event.preventDefault();

        // Buat dan submit form logout dinamis
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }


    function exportToCSV(data, filename = 'sensor-data.csv') {
        const csvRows = [];
        const headers = ['waktu', 'suhu', 'kelembapan', 'ph', 'gas'];
        csvRows.push(headers.join(','));

        for (const row of data) {
            const values = [
                row.created_at,
                row.field1,
                row.field2,
                row.field3,
                row.field4
            ];
            csvRows.push(values.join(','));
        }

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('generateReportBtn');
        if (button) {
            button.addEventListener('click', () => {
                fetch('https://api.thingspeak.com/channels/2954806/feeds.json?api_key=SFWJ5UAULE9O6S0L')
                    .then(response => response.json())
                    .then(result => {
                        const data = result.feeds;
                        if (Array.isArray(data) && data.length > 0) {
                            exportToCSV(data, 'laporan-sensor.csv');
                        } else {
                            alert("Data tidak tersedia atau kosong.");
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                        alert("Terjadi kesalahan saat mengambil data.");
                    });
            });
        }
    });
    document.getElementById('station-menu').addEventListener('click', function() {
    const submenu = document.getElementById('station-submenu');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block';
    } else {
        submenu.style.display = 'none';
    }
});

</script>
@endsection