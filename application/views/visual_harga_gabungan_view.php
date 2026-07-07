<div class="container-fluid">
    <section class="content-header mb-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="font-weight-bold mb-2" style="color: #2c3e50;">
                        <i class="fas fa-chart-line mr-2" style="color: #3498db;"></i>
                        Laporan & Perbandingan Harga
                    </h1>
                    <p class="text-muted mb-0">Analisis harga komoditas dan perbandingan antar periode</p>
                </div>
                <a href="<?= site_url('Dashboard_new/index') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </section>

    <ul class="nav nav-tabs nav-fill nav-justified" id="hargaTab" role="tablist">
    <li class="nav-item" role="presentation">
        
        <a class="nav-link py-3 font-weight-bold <?= ($active_tab == 'laporan') ? 'active' : '' ?>"
           href="<?= site_url('Dashboard_new/visual_harga_gabungan') ?>?active_tab=laporan&komoditas_laporan=<?= urlencode($jenis_terpilih) ?>&bulan_harian=<?= urlencode($selected_bulan_harian) ?>&tahun_harian=<?= urlencode($selected_tahun_harian) ?>&tahun_bulanan=<?= urlencode($selected_tahun_bulanan) ?>&komoditas1=<?= urlencode($selected_komoditas1) ?>&komoditas2=<?= urlencode($selected_komoditas2) ?>&tahun_compare=<?= urlencode($selected_tahun_compare) ?>">
            
            <i class="fas fa-file-alt mr-2"></i>Laporan Harga Satuan
        </a>

    </li>
    <li class="nav-item" role="presentation">

        <a class="nav-link py-3 font-weight-bold <?= ($active_tab == 'perbandingan') ? 'active' : '' ?>"
           href="<?= site_url('Dashboard_new/visual_harga_gabungan') ?>?active_tab=perbandingan&komoditas_laporan=<?= urlencode($jenis_terpilih) ?>&bulan_harian=<?= urlencode($selected_bulan_harian) ?>&tahun_harian=<?= urlencode($selected_tahun_harian) ?>&tahun_bulanan=<?= urlencode($selected_tahun_bulanan) ?>&komoditas1=<?= urlencode($selected_komoditas1) ?>&komoditas2=<?= urlencode($selected_komoditas2) ?>&tahun_compare=<?= urlencode($selected_tahun_compare) ?>">
            
            <i class="fas fa-balance-scale mr-2"></i>Perbandingan Harga
        </a>

    </li>
</ul>

    <div class="tab-content" id="hargaTabContent">
        
        <div class="tab-pane fade <?= ($active_tab == 'laporan') ? 'show active' : '' ?>" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
            
            <section class="content py-4">
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                            <div class="card-body p-4">
                                <div class="row align-items-center text-white">
                                    <div class="col-auto">
                                        <div class="bg-white bg-opacity-25 rounded-circle p-4">
                                            <i class="fas fa-tag fa-3x text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <?php
                                        $judul_harga = "Harga Rata-Rata";
                                        $tanggal_display = date('d M Y'); 
                                        $harga_display = "Belum Tersedia";
                                        $sumber_data_text = "Data harga untuk hari ini belum diproses";
                                        $is_data_today = false; 
                                        if (isset($harga_hari_ini) && is_array($harga_hari_ini) &&
                                            isset($harga_hari_ini['nilai_rata_rata']) && $harga_hari_ini['nilai_rata_rata'] > 0 &&
                                            isset($harga_hari_ini['tanggal'])) {

                                            $data_tanggal = $harga_hari_ini['tanggal'];
                                            $tanggal_display = date('d M Y', strtotime($data_tanggal)); 
                                            $harga_display = "Rp " . number_format($harga_hari_ini['nilai_rata_rata'], 0, ',', '.');

                                            if ($data_tanggal == date('Y-m-d')) {
                                                $is_data_today = true;
                                                $judul_harga = "Harga Rata-Rata Hari Ini";
                                                if (isset($harga_hari_ini['jumlah_sumber_data'])) {
                                                    $sumber_data_text = "Berdasarkan " . $harga_hari_ini['jumlah_sumber_data'] . " sumber data";
                                                } else {
                                                    $sumber_data_text = "Berdasarkan 1 sumber data"; 
                                                }
                                            } else {
                                                $judul_harga = "Harga Rata-Rata Terakhir";
                                                $sumber_data_text = "Data per " . $tanggal_display . " (belum ada update hari ini)";
                                            }
                                        }
                                        ?>

                                        <div class="text-uppercase font-weight-bold mb-2" style="letter-spacing: 1px;">
                                            <?= $judul_harga ?>
                                            <?php if ($is_data_today): ?>
                                                <span class="badge badge-light ml-2"><?= $tanggal_display ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="h1 font-weight-bold mb-2" style="font-size: 2.5rem;">
                                            <?= $harga_display ?>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <small><?= $sumber_data_text ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 font-weight-bold text-secondary">
                                    <i class="fas fa-sliders-h mr-2"></i>Filter Laporan
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="get" action="<?= site_url('Dashboard_new/visual_harga_gabungan'); ?>" class="form-row align-items-end">
                                    <input type="hidden" name="active_tab" value="laporan">
                                    
                                    <input type="hidden" name="komoditas1" value="<?= $selected_komoditas1; ?>">
                                    <input type="hidden" name="komoditas2" value="<?= $selected_komoditas2; ?>">
                                    <input type="hidden" name="tahun_compare" value="<?= $selected_tahun_compare; ?>">
                                    
                                    <div class="col-lg-3 mb-3 mb-lg-0">
                                        <label for="pilihKomoditas" class="font-weight-bold text-secondary">
                                            <i class="fas fa-filter mr-2"></i>Pilih Laporan
                                        </label>
                                        <select id="pilihKomoditas" name="komoditas_laporan" class="custom-select shadow-sm">
                                            <option value="telur" <?= ($jenis_terpilih == 'telur') ? 'selected' : '' ?>>Harga Telur Layer</option>
                                            <option value="jagung" <?= ($jenis_terpilih == 'jagung') ? 'selected' : '' ?>>Harga Jagung</option>
                                            <option value="katul" <?= ($jenis_terpilih == 'katul') ? 'selected' : '' ?>>Harga Katul</option>
                                            <option value="afkir" <?= ($jenis_terpilih == 'afkir') ? 'selected' : '' ?>>Harga Afkir</option>
                                            <option value="telur_puyuh" <?= ($jenis_terpilih == 'telur_puyuh') ? 'selected' : '' ?>>Harga Telur Puyuh</option>
                                            <option value="telur_bebek" <?= ($jenis_terpilih == 'telur_bebek') ? 'selected' : '' ?>>Harga Telur Bebek</option>
                                            <option value="bebek_pedaging" <?= ($jenis_terpilih == 'bebek_pedaging') ? 'selected' : '' ?>>Harga Bebek Pedaging</option>
                                            <option value="live_bird" <?= ($jenis_terpilih == 'live_bird') ? 'selected' : '' ?>>Harga Live Bird</option>
                                            <option value="pakan_broiler" <?= ($jenis_terpilih == 'pakan_broiler') ? 'selected' : '' ?>>Pakan Komplit Broiler</option>
                                            <option value="doc" <?= ($jenis_terpilih == 'doc') ? 'selected' : '' ?>>DOC</option>
                                            <option value="konsentrat_layer" <?= ($jenis_terpilih == 'konsentrat_layer') ? 'selected' : '' ?>>Avg Harga Konsentrat Layer</option>
                                            <option value="hpp_konsentrat_layer" <?= ($jenis_terpilih == 'hpp_konsentrat_layer') ? 'selected' : '' ?>>Avg HPP Konsentrat Layer</option>
                                            <option value="hpp_komplit_layer" <?= ($jenis_terpilih == 'hpp_komplit_layer') ? 'selected' : '' ?>>Avg HPP Komplit Layer</option>
                                            <option value="cost_komplit_broiler" <?= ($jenis_terpilih == 'cost_komplit_broiler') ? 'selected' : '' ?>>Avg Cost Komplit Broiler</option>
                                            <option value="hpp_broiler" <?= ($jenis_terpilih == 'hpp_broiler') ? 'selected' : '' ?>>Avg HPP Broiler</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                                        <label for="filter_bulan_harian" class="font-weight-bold text-secondary">
                                            <i class="far fa-calendar-alt mr-1"></i>Grafik Harian
                                        </label>
                                        <div class="input-group">
                                            <select name="bulan_harian" id="filter_bulan_harian" class="custom-select">
                                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                                    <?php $bulan_val = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
                                                    <option value="<?= $bulan_val; ?>" <?= ($selected_bulan_harian == $bulan_val) ? 'selected' : ''; ?>>
                                                        <?= date('F', mktime(0, 0, 0, $m, 10)); ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                            <select name="tahun_harian" class="custom-select ml-2">
                                                <?php $tahun_sekarang = date('Y'); ?>
                                                <?php for ($y = $tahun_sekarang; $y >= $tahun_sekarang - 5; $y--): ?>
                                                    <option value="<?= $y; ?>" <?= ($selected_tahun_harian == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                                        <label for="filter_tahun_bulanan" class="font-weight-bold text-secondary">
                                            <i class="far fa-calendar-alt mr-1"></i>Grafik Bulanan
                                        </label>
                                        <select name="tahun_bulanan" id="filter_tahun_bulanan" class="custom-select">
                                            <?php for ($y = $tahun_sekarang; $y >= $tahun_sekarang - 5; $y--): ?>
                                                <option value="<?= $y; ?>" <?= ($selected_tahun_bulanan == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                            <i class="fas fa-sync-alt mr-2"></i>Terapkan Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-gradient-success text-white border-0">
                                <h5 class="mb-0 font-weight-bold">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Grafik Harga Harian
                                    <?php 
                                        $titles = [
                                            'jagung' => 'Jagung', 'katul' => 'Katul', 'afkir' => 'Afkir',
                                            'telur_puyuh' => 'Telur Puyuh', 'telur_bebek' => 'Telur Bebek',
                                            'bebek_pedaging' => 'Bebek Pedaging', 'live_bird' => 'Live Bird',
                                            'pakan_broiler' => 'Pakan Komplit Broiler', 'doc' => 'DOC',
                                            'konsentrat_layer' => 'Avg Harga Konsentrat Layer',
                                            'hpp_konsentrat_layer' => 'Avg HPP Konsentrat Layer',
                                            'hpp_komplit_layer' => 'Avg HPP Komplit Layer',
                                            'cost_komplit_broiler' => 'Avg Cost Komplit Broiler',
                                            'hpp_broiler' => 'Avg HPP Broiler',
                                            'telur' => 'Telur Layer'
                                        ];
                                        echo $titles[$jenis_terpilih] ?? 'Telur Layer';
                                    ?>
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div style="height: 400px;">
                                    <canvas id="hargaHarianChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-gradient-primary text-white border-0">
                                <h5 class="mb-0 font-weight-bold">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Grafik Bulanan
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div style="height: 350px;">
                                    <canvas id="hargaBulananChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <div class="tab-pane fade <?= ($active_tab == 'perbandingan') ? 'show active' : '' ?>" id="perbandingan" role="tabpanel" aria-labelledby="perbandingan-tab">
            
            <section class="content py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 font-weight-bold text-secondary">
                                    <i class="fas fa-sliders-h mr-2"></i>Filter Perbandingan
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="<?= site_url('Dashboard_new/visual_harga_gabungan') ?>" method="GET">
                                    <input type="hidden" name="active_tab" value="perbandingan">
                                    
                                    <input type="hidden" name="komoditas_laporan" value="<?= $jenis_terpilih; ?>">
                                    <input type="hidden" name="bulan_harian" value="<?= $selected_bulan_harian; ?>">
                                    <input type="hidden" name="tahun_harian" value="<?= $selected_tahun_harian; ?>">
                                    <input type="hidden" name="tahun_bulanan" value="<?= $selected_tahun_bulanan; ?>">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3 mb-lg-0">
                                            <label for="komoditas1" class="font-weight-bold text-secondary mb-2">
                                                <i class="fas fa-box mr-1"></i>Komoditas 1
                                            </label>
                                            <select id="komoditas1" name="komoditas1" class="custom-select custom-select-lg shadow-sm">
                                                <?php foreach ($all_komoditas as $value => $text): ?>
                                                    <option value="<?= $value ?>" <?= ($selected_komoditas1 == $value) ? 'selected' : '' ?>>
                                                        <?= $text ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-3 mb-lg-0">
                                            <label for="komoditas2" class="font-weight-bold text-secondary mb-2">
                                                <i class="fas fa-box mr-1"></i>Komoditas 2
                                            </label>
                                            <select id="komoditas2" name="komoditas2" class="custom-select custom-select-lg shadow-sm">
                                                <?php foreach ($all_komoditas as $value => $text): ?>
                                                    <option value="<?= $value ?>" <?= ($selected_komoditas2 == $value) ? 'selected' : '' ?>>
                                                        <?= $text ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 mb-3 mb-lg-0">
                                            <label for="tahun_filter" class="font-weight-bold text-secondary mb-2">
                                                <i class="far fa-calendar-alt mr-1"></i>Tahun
                                            </label>
                                            <select id="tahun_filter" name="tahun_compare" class="custom-select custom-select-lg shadow-sm">
                                                <option value="semua" <?= ($selected_tahun_compare == 'semua') ? 'selected' : '' ?>>Semua Tahun</option>
                                                <?php 
                                                $current_year = date('Y');
                                                for ($y = $current_year; $y >= $current_year - 5; $y--): 
                                                ?>
                                                    <option value="<?= $y ?>" <?= ($selected_tahun_compare == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2">
                                            <label class="d-block mb-2 invisible">Submit</label>
                                            <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                                                <i class="fas fa-search mr-2"></i> Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3498db !important;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 mr-3">
                                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Komoditas 1</h6>
                                        <h4 class="mb-0 font-weight-bold text-primary">
                                            <?= $all_komoditas[$selected_komoditas1] ?? 'Harga Telur Layer' ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #27ae60 !important;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 mr-3">
                                        <i class="fas fa-chart-line fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Komoditas 2</h6>
                                        <h4 class="mb-0 font-weight-bold text-success">
                                            <?= $all_komoditas[$selected_komoditas2] ?? 'Harga Jagung' ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-lg">
                            <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                                <h3 class="mb-0 font-weight-bold text-white">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Grafik Perbandingan Harga Bulanan
                                    <?php if ($selected_tahun_compare != 'semua'): ?>
                                        <span class="badge badge-light ml-2"><?= $selected_tahun_compare ?></span>
                                    <?php endif; ?>
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <?php if (empty(json_decode($compare_chart_labels)) || json_decode($compare_chart_labels) == []): ?>
                                    <div class="alert alert-info text-center py-5">
                                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                                        <h5>Tidak ada data untuk tahun yang dipilih</h5>
                                        <p class="mb-0">Silakan pilih tahun yang berbeda atau pilih "Semua Tahun"</p>
                                    </div>
                                <?php else: ?>
                                    <div style="height: 500px;">
                                        <canvas id="hargaCompareChart"></canvas>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>

<style>
/* Style dengan Blue Palette */
.bg-gradient-success {
    background: linear-gradient(87deg, #27ae60 0, #229954 100%) !important;
}
.bg-gradient-primary {
    background: linear-gradient(87deg, #3498db 0, #2980b9 100%) !important;
}
.bg-gradient-purple {
    background: linear-gradient(87deg, #5dade2 0, #3498db 100%) !important;
}
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08) !important;
}
.custom-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}
.bg-opacity-25 {
    opacity: 0.25;
}
.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

/* Style dari visual_harga_compare_view.php */
.bg-opacity-10 {
    background-color: rgba(52, 152, 219, 0.1) !important;
}
.bg-primary.bg-opacity-10 {
    background-color: rgba(52, 152, 219, 0.1) !important;
}
.bg-success.bg-opacity-10 {
    background-color: rgba(39, 174, 96, 0.1) !important;
}
.border-right {
    border-right: 1px solid #dee2e6;
}
@media (max-width: 768px) {
    .border-right {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
    }
    .border-right:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
}

/* Style baru untuk Tab dengan Blue Palette */
.nav-tabs .nav-link {
    border: none;
    border-top-left-radius: .35rem;
    border-top-right-radius: .35rem;
    color: #525f7f;
    background-color: #f6f9fc;
    border-bottom: 3px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #3498db;
    background-color: #ffffff;
    border-bottom: 3px solid #3498db;
    box-shadow: 0 -0.5rem 1rem rgba(0,0,0,0.05);
}
.tab-content {
    background-color: #ffffff;
    border-bottom-left-radius: .35rem;
    border-bottom-right-radius: .35rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
}

.btn-primary {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
}

.text-primary {
    color: #3498db !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Fungsi helper universal
    const formatRupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

    // ==========================================================
    // SCRIPT UNTUK TAB 1 (LAPORAN)
    // ==========================================================

    // *** PERUBAHAN: Listener 'change' untuk 'pilihKomoditas' Dihapus ***
    // const selectKomoditas = document.getElementById('pilihKomoditas');
    // selectKomoditas.addEventListener('change', function() {
    //     ... (INI DIHAPUS KARENA SEKARANG MENJADI BAGIAN DARI FORM SUBMIT)
    // });

    // Tooltip options untuk chart laporan
    const tooltipOptionsLaporan = {
        plugins: {
            tooltip: {
                callbacks: {
                    label: context => {
                        let label = context.dataset.label || '';
                        if (label) label += ': ';
                        if (context.parsed.y !== null) label += formatRupiah(context.parsed.y);
                        return label;
                    }
                },
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 }
            },
            legend: {
                labels: {
                    font: { size: 13 },
                    padding: 15
                }
            }
        },
        scales: {
            y: { 
                ticks: { 
                    callback: value => formatRupiah(value),
                    font: { size: 11 }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: 11 } },
                grid: { display: false }
            }
        },
        maintainAspectRatio: false,
        responsive: true,
    };

    // Chart Harian (Tab Laporan)
    const ctxHarian = document.getElementById('hargaHarianChart');
    if (ctxHarian) {
        new Chart(ctxHarian, {
            type: 'line',
            data: {
                labels: <?php echo $chart_harian_labels; ?>,
                datasets: [{
                    label: 'Harga Rata-Rata (Rp)',
                    data: <?php echo $chart_harian_data; ?>,
                    borderColor: '#2dce89',
                    backgroundColor: 'rgba(45, 206, 137, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2dce89',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: tooltipOptionsLaporan
        });
    }

    // Chart Bulanan (Tab Laporan)
    const ctxBulanan = document.getElementById('hargaBulananChart');
    if (ctxBulanan) {
        new Chart(ctxBulanan, {
            type: 'line',
            data: {
                labels: <?php echo $chart_bulanan_labels; ?>,
                datasets: [{
                    label: 'Harga Rata-Rata (Rp)',
                    data: <?php echo $chart_bulanan_data; ?>,
                    borderColor: '#5e72e4',
                    backgroundColor: 'rgba(94, 114, 228, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#5e72e4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: tooltipOptionsLaporan
        });
    }

    // ==========================================================
    // SCRIPT UNTUK TAB 2 (PERBANDINGAN)
    // ==========================================================

    // *** PERUBAHAN: Variabel diubah agar tidak bentrok ***
    const compareChartLabels = <?php echo $compare_chart_labels; ?>;
    const compareChartDatasets = <?php echo $compare_chart_datasets; ?>;
    
    // Tooltip options untuk chart perbandingan
    const tooltipOptionsCompare = {
        plugins: {
            tooltip: {
                callbacks: {
                    label: context => {
                        let label = context.dataset.label || '';
                        if (label) label += ': ';
                        if (context.parsed.y !== null) label += formatRupiah(context.parsed.y);
                        return label;
                    }
                },
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 }
            },
            legend: {
                labels: {
                    font: { size: 14, weight: 'bold' }, // Sedikit beda dari aslinya
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        },
        scales: {
            y: { 
                ticks: { 
                    callback: value => formatRupiah(value),
                    font: { size: 11 }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                ticks: { font: { size: 11 } },
                grid: { display: false }
            }
        },
        maintainAspectRatio: false,
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false
        }
    };

    // Chart Perbandingan (Tab Perbandingan)
    if (compareChartLabels && compareChartLabels.length > 0) {
        const ctxCompare = document.getElementById('hargaCompareChart');
        if (ctxCompare) {
            const enhancedDatasets = compareChartDatasets.map((dataset, index) => {
                return {
                    ...dataset,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointBackgroundColor: dataset.borderColor,
                    fill: true
                };
            });

            new Chart(ctxCompare, {
                type: 'line',
                data: {
                    labels: compareChartLabels,
                    datasets: enhancedDatasets
                },
                options: tooltipOptionsCompare
            });
        }
    }
});
</script>