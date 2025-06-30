@extends('layouts.app') 

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container mt-4">
    <h4 class="mb-4">Data Sensor dari ThingSpeak</h4>

    {{-- Alert untuk error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert untuk pesan kosong --}}
    @if(isset($error))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <button onclick="exportToCSV()" class="btn btn-success" id="exportBtn">Export CSV</button>
        <button onclick="location.reload()" class="btn btn-primary">Refresh</button>
        <span id="dataCount" class="badge bg-info ms-2">Data: {{ count($feeds ?? []) }} entries</span>
    </div>

    @if(!empty($feeds) && count($feeds) > 0)
        {{-- Grafik Chart --}}
        <div class="card mb-4">
            <div class="card-body">
                <canvas id="sensorChart" height="100"></canvas>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Waktu</th>
                        <th>Suhu (°C)</th>
                        <th>Kelembaban (%)</th>
                        <th>pH Air</th>
                        <th>Gas (ppm)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feeds as $feed)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($feed['created_at'])->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $feed['field1'] ?? '-' }}</td>
                            <td>{{ $feed['field2'] ?? '-' }}</td>
                            <td>{{ $feed['field3'] ?? '-' }}</td>
                            <td>{{ $feed['field4'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h5>Tidak ada data sensor</h5>
            <p>Belum ada data yang tersedia dari ThingSpeak atau terjadi masalah koneksi.</p>
            <button onclick="location.reload()" class="btn btn-primary">Coba Lagi</button>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    
    const feeds = @json($feeds??[]);
    console.log('Feeds data loaded:', feeds);
    console.log('Feeds count:', feeds.length);

    // Cek apakah ada data sebelum membuat chart
    if (feeds && feeds.length > 0) {
        const labels = feeds.map(feed =>
            new Date(feed.created_at).toLocaleString('id-ID')
        );

        const suhu = feeds.map(feed => parseFloat(feed.field1) || 0);
        const kelembaban = feeds.map(feed => parseFloat(feed.field2) || 0);
        const ph = feeds.map(feed => parseFloat(feed.field3) || 0);
        const gas = feeds.map(feed => parseFloat(feed.field4) || 0);

        // Chart
        const ctx = document.getElementById('sensorChart');
        if (ctx) {
            const chart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Suhu (°C)',
                            data: suhu,
                            borderColor: 'red',
                            backgroundColor: 'rgba(255,0,0,0.1)',
                            fill: true,
                        },
                        {
                            label: 'Kelembaban (%)',
                            data: kelembaban,
                            borderColor: 'blue',
                            backgroundColor: 'rgba(0,0,255,0.1)',
                            fill: true,
                        },
                        {
                            label: 'pH Air',
                            data: ph,
                            borderColor: 'green',
                            backgroundColor: 'rgba(0,255,0,0.1)',
                            fill: true,
                        },
                        {
                            label: 'Gas (ppm)',
                            data: gas,
                            borderColor: 'orange',
                            backgroundColor: 'rgba(255,165,0,0.1)',
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Grafik Sensor dari ThingSpeak' }
                    },
                    scales: {
                        y: { beginAtZero: true },
                        x: {
                            ticks: {
                                maxRotation: 90,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 20
                            }
                        }
                    }
                }
            });
        }
    } else {
        console.warn('No data available for chart');
        // Hide chart if no data
        const chartContainer = document.querySelector('.card');
        if (chartContainer) {
            chartContainer.style.display = 'none';
        }
    }

    // Export CSV 
    function exportToCSV() {
        const exportBtn = document.getElementById('exportBtn');
        
        if (!feeds || feeds.length === 0) {
            alert('Tidak ada data untuk diekspor. Silakan refresh halaman atau cek koneksi ThingSpeak.');
            console.error('No feeds data available for export');
            return;
        }

        try {
            // Disable button sementara
            exportBtn.disabled = true;
            exportBtn.textContent = 'Mengekspor...';
            
            // Buat header CSV
            let csv = 'Waktu,Suhu (°C),Kelembaban (%),pH Air,Gas (ppm)\n';
            
            // Loop through data dan tambahkan ke CSV
            feeds.forEach(feed => {
                const waktu = new Date(feed.created_at).toLocaleString('id-ID');
                const suhu = feed.field1 || '-';
                const kelembaban = feed.field2 || '-';
                const ph = feed.field3 || '-';
                const gas = feed.field4 || '-';
                
                // Escape quotes dan wrap dengan quotes jika diperlukan
                const formatField = (field) => {
                    if (typeof field === 'string' && (field.includes(',') || field.includes('"') || field.includes('\n'))) {
                        return '"' + field.replace(/"/g, '""') + '"';
                    }
                    return field;
                };
                
                csv += `${formatField(waktu)},${formatField(suhu)},${formatField(kelembaban)},${formatField(ph)},${formatField(gas)}\n`;
            });

            // Buat dan download file
            const BOM = '\uFEFF'; // Byte Order Mark untuk Excel compatibility
            const blob = new Blob([BOM + csv], { 
                type: 'text/csv;charset=utf-8;' 
            });
            
            // Buat link download
            const link = document.createElement('a');
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `data_thingspeak_${new Date().toISOString().slice(0,10)}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Cleanup
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                }, 100);
                
                console.log('CSV berhasil diexport, total rows:', feeds.length);
                alert(`CSV berhasil diexport dengan ${feeds.length} baris data!`);
            } else {
                // Fallback untuk browser lama
                alert('Browser Anda tidak mendukung download otomatis. Silakan copy data CSV dari console.');
                console.log(csv);
            }
            
        } catch (error) {
            console.error('Error saat export CSV:', error);
            alert('Terjadi kesalahan saat export CSV: ' + error.message);
        } finally {
            // Re-enable button
            exportBtn.disabled = false;
            exportBtn.textContent = 'Export CSV';
        }
    }



    // Auto-update data count in badge
    document.addEventListener('DOMContentLoaded', function() {
        const dataCount = document.getElementById('dataCount');
        if (dataCount) {
            const count = feeds ? feeds.length : 0;
            dataCount.textContent = `Data: ${count} entries`;
            dataCount.className = count > 0 ? 'badge bg-success ms-2' : 'badge bg-danger ms-2';
        }
    });
</script>