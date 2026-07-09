<div class="container-fluid">
    <!-- Header Section -->
    <section class="content-header mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center p-3 bg-white shadow-sm" style="border-radius: 2rem;">
                <a href="<?= site_url('Dashboard_new/index') ?>" class="btn btn-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                    <i class="fas fa-home fa-lg text-white"></i>
                </a>
                <h1 class="font-weight-bold text-dark mb-0 mx-auto" style="font-size: 2rem;">
                    Kasus Penyakit
                </h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="col-sm-12">
            <!-- Filter Card -->
             <form action="<?= site_url('Dashboard_new/visual_kasus_penyakit') ?>" method="post" class="row g-3 align-items-end" id="mainFilterForm">
                <input type="hidden" name="filter_area_id" id="filter_area_id" value="">
                <input type="hidden" name="filter_area_name" id="filter_area_name" value="">
                
                
            </form>
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #3498db 100%);">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-filter mr-2"></i>Filter Laporan
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="<?= site_url('Dashboard_new/visual_kasus_penyakit') ?>" method="post" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label for="tahun" class="form-label fw-bold text-secondary">
                                <i class="fas fa-calendar mr-2"></i>Tahun
                            </label>
                            <select name="tahun" id="tahun" class="form-control form-select" style="border-radius: 10px; height: 45px;">
                                <option value="0">-- Semua Tahun --</option>
                                <?php for ($i = date('Y'); $i >= date('Y') - 7; $i--): ?>
                                    <option value="<?= $i; ?>" <?= ($selected_year == $i) ? 'selected' : ''; ?>><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px; height: 45px;">
                                <i class="fas fa-search mr-2"></i>Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0" style="color: #2c3e50;">
                            <i class="fas fa-chart-bar mr-2" style="color: #5dade2;"></i>Grafik Kasus per Bulan
                        </h3>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($chart_labels) && !empty(json_decode($chart_labels))): ?>
                        <div style="position: relative; height: 400px;">
                            <canvas id="kasusStackedChart"></canvas>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-4x mb-3 text-muted" style="opacity: 0.3;"></i>
                            <p class="text-muted h5">Tidak ada data untuk ditampilkan pada periode yang dipilih.</p>
                            <p class="text-muted small">Silakan pilih tahun yang berbeda</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pivot Table Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h3 class="card-title font-weight-bold mb-0" style="color: #2c3e50;">
                        <i class="fas fa-table mr-2" style="color: #3498db;"></i>Tabel Pivot Kasus per Area
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="min-width: 800px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr class="text-center">
                                    <th style="width: 200px; border-top: none; position: sticky; left: 0; background-color: #f8f9fa; z-index: 10;">
                                        <i class="fas fa-map-marker-alt mr-2" style="color: #3498db;"></i>Area
                                    </th>
                                    <?php foreach ($pivot_table_categories as $kategori): ?>
                                        <th style="border-top: none; min-width: 100px;">
                                            <?= htmlspecialchars($kategori); ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pivot_table_data)): ?>
                                    <?php else: ?>
                                    <?php foreach ($pivot_table_data as $area_data): ?>
                                        <?php 
                                            $area_id = $area_data['master_area_id'] ?? 0; // Ambil ID Area
                                            $area_name = htmlspecialchars($area_data['nama_area']);
                                        ?>
                                        <tr class="clickable-area" 
                                            data-area-id="<?= $area_id; ?>" 
                                            style="transition: all 0.3s ease; cursor: pointer;">
                                            
                                            <td style="position: sticky; left: 0; background-color: white; z-index: 5;">
                                                <strong style="color: #2c3e50;">
                                                    <i class="fas fa-map-pin mr-2" style="color: #3498db;"></i>
                                                    <?= $area_name; ?>
                                                </strong>
                                            </td>
                                            <?php foreach ($pivot_table_categories as $kategori): ?>
                                                <td class="text-center">
                                                    <?php 
                                                        $nilai = $area_data[$kategori] ?? 0;
                                                        $badge_class = $nilai > 0 ? 'badge-primary' : 'badge-light';
                                                    ?>
                                                    <span class="badge <?= $badge_class ?> px-3 py-2" style="font-size: 13px; min-width: 50px;">
                                                        <?= $nilai; ?>
                                                    </span>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail Cases Card -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 pb-3">
        <h3 class="card-title font-weight-bold mb-0" style="color: #2c3e50;">
            <i class="fas fa-clipboard-list mr-2" style="color: #5dade2;"></i>Rincian Laporan Kasus
        </h3>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="detailCaseTable" style="width:100%;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 180px;">Waktu Kunjungan</th>
                        <th style="width: 130px;">Area Farm</th>
                        <th style="width: 180px;">Nama Farm</th>
                        <th style="width: 220px;">Jenis Kasus</th>
                        <th style="min-width: 250px;">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kasus_detail_list)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block text-muted" style="opacity: 0.3;"></i>
                                <span class="text-muted">Tidak ada rincian kasus untuk ditampilkan.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($kasus_detail_list as $row): ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <?= $no++; ?>
                                </td>
                                <td class="align-middle text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-clock mr-2 text-primary" style="font-size: 14px;"></i>
                                        <span><?= date('d M Y', strtotime($row['waktu_kunjungan'])); ?></span>
                                        <span class="text-muted small ml-2"><?= date('H:i', strtotime($row['waktu_kunjungan'])); ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span style="color: #000000ff;">
                                        <?= htmlspecialchars($row['nama_area'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span style="color: #000000ff;">
                                        <?= htmlspecialchars($row['nama_farm']); ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span style="color: #000000ff;">
                                        <?= htmlspecialchars($row['jenis_kasus']); ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <?php 
                                        $alamat = $row['location_address'] ?? '-';
                                        // Trim dan cek jika kosong
                                        $alamat = trim($alamat);
                                        if (empty($alamat) || $alamat === '-') {
                                            $alamat = '-';
                                            $icon_class = 'fas fa-map-marker-alt text-muted';
                                        } else {
                                            $icon_class = 'fas fa-map-marker-alt text-success';
                                        }
                                    ?>
                                    <div class="d-flex align-items-start">
                                        <i class="<?= $icon_class ?> mr-2 mt-1" style="font-size: 13px;"></i>
                                        <span style="color: #000000ff; font-size: 13px; line-height: 1.4;">
                                            <?= htmlspecialchars($alamat); ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Modern Styling - Blue Palette */
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .form-control, .form-select {
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #3498db 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Scrollbar Styling - Blue */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #3498db;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #667eea;
    }


    .table-active-filter {
        border-left: 5px solid #3498db;
        /* background-color: #eaf4ff !important; */
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
let kasusChart = null;
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
let csrfTokenName = '<?= $this->security->get_csrf_token_name(); ?>';
// Tahan ID Area yang sedang aktif
let activeAreaId = null;

document.addEventListener('DOMContentLoaded', function () {
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }
    
    // Label bulan yang digunakan di View (Jan, Feb, ...)
    const masterChartLabels = <?= $chart_labels; ?>; 
    const initialDatasets = <?= $chart_datasets; ?>; 
    
    renderKasusChart(masterChartLabels, initialDatasets);

    const rows = document.querySelectorAll('.clickable-area');
    
    rows.forEach((row, index) => {
        row.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah default action jika ada

            // Dapatkan data area yang diklik
            const areaId = this.getAttribute('data-area-id');
            const areaNameElement = this.querySelector('strong');
            const areaName = areaNameElement ? areaNameElement.textContent.trim() : 'Unknown';
            
            // Logika untuk menonaktifkan filter jika area yang sama diklik lagi
            if (activeAreaId === areaId) {
                // Area yang sama diklik lagi, nonaktifkan filter
                activeAreaId = null;
                // Hapus highlight dari semua baris
                document.querySelectorAll('.clickable-area').forEach(r => {
                    r.classList.remove('table-active-filter');
                });
                // Panggil filterByArea dengan ID 0 atau null untuk memuat data keseluruhan
                filterByArea(0, 'Semua Area');
                // Hentikan eksekusi
                return; 
            }

            // Atur activeAreaId ke ID yang baru diklik
            activeAreaId = areaId;

            // Highlight row yang diklik
            document.querySelectorAll('.clickable-area').forEach(r => {
                r.classList.remove('table-active-filter');
            });
            this.classList.add('table-active-filter');
            
            filterByArea(areaId, areaName);
        });
    });
    
    // Aksi saat tombol Tampilkan di Filter Tahun diklik 
    document.querySelector('.card-body form').addEventListener('submit', function(e) {
        // Hapus highlight dan active area ID saat filter tahun di-submit
        document.querySelectorAll('.clickable-area').forEach(r => {
            r.classList.remove('table-active-filter');
        });
        activeAreaId = null;
    });

});

function renderKasusChart(labels, datasets) {
    const chartWrapper = document.getElementById('kasusStackedChart').parentElement;
    
    if (kasusChart) {
        kasusChart.destroy();
    }
    
    // Check jika datasets kosong (tidak ada data)
    let hasData = datasets.some(dataset => dataset.data.some(val => val !== 0));

    if (!hasData) {
        chartWrapper.innerHTML = `
            <div class="text-center py-5" style="position: relative; height: 400px;">
                <i class="fas fa-chart-bar fa-4x mb-3 text-muted" style="opacity: 0.3;"></i>
                <p class="text-muted h5">Tidak ada data untuk ditampilkan pada periode/area ini.</p>
            </div>
        `;
        return;
    }
    
    // Recreate canvas jika sebelumnya ada pesan "Tidak ada data"
    if(chartWrapper.querySelector('#kasusStackedChart') === null) {
        chartWrapper.innerHTML = '<canvas id="kasusStackedChart"></canvas>';
    }

    const stackedCtx = document.getElementById('kasusStackedChart');

    // Tentukan total maksimum untuk sumbu Y, misal 10% lebih besar dari nilai maks
    let maxTotal = 0;
    labels.forEach((label, index) => {
        let monthlyTotal = 0;
        datasets.forEach(dataset => {
            monthlyTotal += dataset.data[index] || 0;
        });
        if (monthlyTotal > maxTotal) {
            maxTotal = monthlyTotal;
        }
    });
    const suggestedMax = Math.ceil(maxTotal * 1.1 / 10) * 10;

    kasusChart = new Chart(stackedCtx, {
        type: 'bar',
        data: { labels: labels, datasets: datasets },
        options: {
            plugins: {
                legend: { display: true, position: 'top', labels: { padding: 15, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } },
                tooltip: {
                    mode: 'index', intersect: false, backgroundColor: 'rgba(0, 0, 0, 0.8)', padding: 12, cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            // Menampilkan angka biasa (jumlah)
                            if (context.parsed.y !== null) { label += context.parsed.y.toLocaleString('id-ID'); } 
                            return label;
                        }
                    }
                },
                datalabels: {
                    color: '#000', // Warna hitam/gelap untuk kontras
                    font: { weight: 'bold', size: 11 },
                    // Formatter untuk menampilkan jumlah kasus (angka bulat > 0)
                    formatter: (value, context) => { 
                        return value > 0 ? value.toFixed(0) : ''; 
                    }
                }
            },
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { 
                    stacked: true, 
                    suggestedMax: suggestedMax > 10 ? suggestedMax : 10, 
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        // Tidak lagi menampilkan '%'
                        callback: function(value, index, values) { 
                            return value.toLocaleString('id-ID'); 
                        },
                        font: { size: 11 }
                    }
                }
            }
        }
    });
}

function createDetailRow(no, data) {
    const waktuFormatted = new Date(data.waktu_kunjungan);
    const datePart = waktuFormatted.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).replace('.', '');
    const timePart = waktuFormatted.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    
    const fullCase = data.jenis_kasus || 'N/A';
    
    // [BARU] Proses alamat
    let alamat = data.location_address || '-';
    alamat = alamat.trim();
    if (alamat === '' || alamat === '-') {
        alamat = '-';
        var iconClass = 'fas fa-map-marker-alt text-muted';
    } else {
        var iconClass = 'fas fa-map-marker-alt text-success';
    }
    
    return `
        <tr>
            <td class="text-center align-middle">
                ${no}
            </td>
            <td class="align-middle text-nowrap">
                <div class="d-flex align-items-center">
                    <i class="far fa-clock mr-2 text-primary" style="font-size: 14px;"></i>
                    <span>${datePart}</span>
                    <span class="text-muted small ml-2">${timePart}</span>
                </div>
            </td>
            <td class="align-middle">
                <span style="font-size: 14px; color: #2c3e50;">
                    ${data.nama_area || 'N/A'}
                </span>
            </td>
            <td class="align-middle">
                <span style="color: #2c3e50; font-size: 14px;">
                    ${data.nama_farm}
                </span>
            </td>
            <td class="align-middle">
                <span style="font-size: 14px; color: #2c3e50;">
                    ${fullCase}
                </span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-start">
                    <i class="${iconClass} mr-2 mt-1" style="font-size: 13px;"></i>
                    <span style="color: #2c3e50; font-size: 13px; line-height: 1.4;">
                        ${alamat}
                    </span>
                </div>
            </td>
        </tr>
    `;
}

function filterByArea(areaId, areaName) {
    console.log('=== filterByArea CALLED ===');
    console.log('Area ID:', areaId);
    console.log('Area Name:', areaName);
    
    const currentYear = document.getElementById('tahun').value;
    
    const chartWrapper = document.getElementById('kasusStackedChart').parentElement;
    const detailTableBody = document.querySelector('#detailCaseTable tbody');
    
    // 1. Tampilkan Loading State
    chartWrapper.style.opacity = '0.5';
    detailTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><i class="fas fa-sync-alt fa-spin fa-2x text-primary mb-3"></i><p class="text-muted">Memuat data untuk Area: <b>${areaName}</b>...</p></td></tr>`;
    
    // Siapkan data POST
    const postData = new URLSearchParams({
        'area_id': areaId,
        'tahun': currentYear,
        [csrfTokenName]: csrfHash
    });
    
    const url = '<?= site_url("Dashboard_new/get_kasus_data_for_area_ajax") ?>';
    
    // 2. Kirim request AJAX
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: postData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.new_csrf_hash) {
            csrfHash = data.new_csrf_hash;
        }
        
        // 3. Update Chart
        const masterLabels = <?= $chart_labels; ?>; 
        renderKasusChart(masterLabels, data.chart_data.datasets);

        // 4. Update Detail Table
        let detailHtml = '';
        if (data.detail_list && data.detail_list.length > 0) {
            data.detail_list.forEach((row, index) => {
                detailHtml += createDetailRow(index + 1, row);
            });
        } else {
            const areaDisplay = areaId > 0 ? `Area: <b>${areaName}</b>` : 'periode yang dipilih';
            detailHtml = `<tr><td colspan="6" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block text-muted" style="opacity: 0.3;"></i>
                            <span class="text-muted">Tidak ada rincian kasus untuk ${areaDisplay}.</span>
                        </td></tr>`;
        }
        detailTableBody.innerHTML = detailHtml;

        chartWrapper.style.opacity = '1';
    })
    .catch(error => {
        console.error('AJAX ERROR:', error);
        
        document.querySelectorAll('.clickable-area').forEach(r => {
            r.classList.remove('table-active-filter');
        });
        activeAreaId = null;

        chartWrapper.style.opacity = '1'; 
        detailTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">
            <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block"></i>
            <span class="text-danger">Gagal memuat data: ${error.message}</span>
        </td></tr>`;
        renderKasusChart([], []);
    });
}
</script>