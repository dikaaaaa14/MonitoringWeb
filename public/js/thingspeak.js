function fetchData() {
    $.ajax({
        url: '/api/fetch-thingspeak',
        method: 'GET',
        success: function(response) {
            updateDashboard(response.data);
        }
    });
}

function updateDashboard(data) {
    // Update cards
    $('#temperature-value').text(data.temperature.toFixed(1) + '°C');
    $('#humidity-value').text(data.humidity.toFixed(0) + '%');
    $('#ph-value').text(data.ph.toFixed(1));
    $('#gas-value').text(data.gas.toFixed(0) + 'ppm');
    
    // Update charts
    updateCharts(data);
    
    // Update table
    updateDataTable(data.records);
}

function updateDataTable(records) {
    let tableBody = $('#sensor-data-body');
    tableBody.empty();
    
    records.forEach(record => {
        tableBody.append(`
            <tr>
                <td>${record.timestamp}</td>
                <td>${record.temperature}°C</td>
                <td>${record.humidity}%</td>
                <td>${record.ph}</td>
                <td>${record.gas}ppm</td>
            </tr>
        `);
    });
}