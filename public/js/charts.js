let tempHumidityChart, phGasChart;

function initCharts() {
    const tempHumidityCtx = document.getElementById('tempHumidityChart').getContext('2d');
    tempHumidityChart = new Chart(tempHumidityCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Temperature (°C)',
                    data: [],
                    borderColor: '#e74a3b',
                    backgroundColor: 'rgba(231, 74, 59, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Humidity (%)',
                    data: [],
                    borderColor: '#36b9cc',
                    backgroundColor: 'rgba(54, 185, 204, 0.1)',
                    tension: 0.1
                }
            ]
        }
    });

    const phGasCtx = document.getElementById('phGasChart').getContext('2d');
    phGasChart = new Chart(phGasCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'pH Level',
                    data: [],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Gas (ppm)',
                    data: [],
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.1)',
                    tension: 0.1
                }
            ]
        }
    });
}

function updateCharts(data) {
    // Update temperature & humidity chart
    tempHumidityChart.data.labels = data.timestamps;
    tempHumidityChart.data.datasets[0].data = data.temperatureData;
    tempHumidityChart.data.datasets[1].data = data.humidityData;
    tempHumidityChart.update();
    
    // Update pH & gas chart
    phGasChart.data.labels = data.timestamps;
    phGasChart.data.datasets[0].data = data.phData;
    phGasChart.data.datasets[1].data = data.gasData;
    phGasChart.update();
}