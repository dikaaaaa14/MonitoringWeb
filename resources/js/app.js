import './bootstrap';
import Chart from 'chart.js/auto';

// Live update function for charts
function updateCharts() {
    fetch('/api/sensor-data/latest')
        .then(response => response.json())
        .then(data => {
            // Update your charts here with new data
            console.log('New sensor data:', data);
        });
}

// Update charts every 30 seconds
setInterval(updateCharts, 30000);

// Initialize any other JS components
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle if needed
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});