@extends('layouts.app')

@section('content')
<div class="content-header">
    <h2>Lokasi Peternakan</h2>
</div>

<!-- Peta -->
<div id="farm-map" style="height: 60vh; border-radius: 8px; margin-bottom: 20px;"></div>

<!-- Informasi Alamat -->
<div class="address-card" style="
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
">
    <h4 style="margin-top: 0;">Kios Telur Puyuh (Mas Adji)</h4>
    <p id="full-address">Klapasawit, Kec. Purwojati, Kabupaten Banyumas, Jawa Tengah 53175 </p>
    <a id="google-maps-link" href="#" target="_blank" style="
        display: inline-block;
        padding: 8px 15px;
        background: #4285F4;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 10px;
    ">
        Buka di Google Maps
    </a>
</div>

<!-- Load Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Koordinat peternakan (ganti dengan data Anda)
    const farmCoords = [-7.47750, 109.12063];
    const farmAddress = "Klapasawit, Kec. Purwojati, Kabupaten Banyumas, Jawa Tengah 53175";

    // Inisialisasi peta
    const map = L.map('farm-map').setView(farmCoords, 15);
    
    // Layer peta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Custom pin icon
    const farmIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/447/447031.png',
        iconSize: [40, 40]
    });

    // Tambahkan pin
    const marker = L.marker(farmCoords, { icon: farmIcon })
        .addTo(map)
        .bindPopup(`
            <b>Lokasi Peternakan</b><br>
            <small>Klik untuk buka Google Maps</small>
        `);

    // Redirect ke Google Maps saat pin diklik
    marker.on('click', function() {
        window.open(
            `https://www.google.com/maps?q=${farmCoords[0]},${farmCoords[1]}`,
            '_blank'
        );
    });

    // Isi informasi alamat
    document.getElementById('full-address').textContent = farmAddress;
    document.getElementById('google-maps-link').href = 
        `https://www.google.com/maps?q=${farmCoords[0]},${farmCoords[1]}`;
</script>

<style>
    #farm-map { 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }
    .address-card {
        transition: all 0.3s ease;
    }
    .address-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection