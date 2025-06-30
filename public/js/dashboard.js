// Toggle Sidebar Dropdown
document.getElementById("station-menu").addEventListener("click", function () {
    document.getElementById("station-submenu").classList.toggle("open");
});

// Toggle Dropdown User
document.getElementById("userInfo").addEventListener("click", function () {
    document.getElementById("userDropdown").classList.toggle("active");
});

// Update DateTime
function updateDateTime() {
    const date = new Date();
    document.getElementById("dateTimeDisplay").textContent = date.toLocaleString();
}
setInterval(updateDateTime, 1000);
updateDateTime();

// Check ThingSpeak Connection
async function updateThingSpeakStatus() {
    try {
        const res = await fetch('/api/check-thingspeak-status');
        const data = await res.json();
        const statusElem = document.getElementById("connectionStatus");

        statusElem.innerHTML = `<span class="status-indicator ${data.connected ? 'status-online' : 'status-offline'}"></span> ${data.connected ? 'Connected' : 'Not Connected'}`;
        document.getElementById("lastUpdated").textContent = new Date().toLocaleString();
    } catch (error) {
        console.error(error);
    }
}

// Update Statistik
async function fetchDashboardStats() {
    const res = await fetch('/api/dashboard-stats');
    const stats = await res.json();

    document.getElementById("totalData").textContent = stats.total;
    document.getElementById("avgTemp").textContent = stats.avgTemp + '°C';
    document.getElementById("avgHumidity").textContent = stats.avgHumidity + '%';
    document.getElementById("humidityProgress").style.width = stats.avgHumidity + '%';
    document.getElementById("deviceOnline").textContent = stats.deviceOnline;
}

// Jalankan saat halaman dimuat
window.onload = function () {
    updateThingSpeakStatus();
    fetchDashboardStats();
};
