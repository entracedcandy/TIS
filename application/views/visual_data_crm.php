<div class="container-fluid">
    <section class="content-header mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center p-3 bg-white shadow-sm" style="border-radius: 2rem;">
                <a href="<?= site_url('Dashboard_new/index') ?>" class="btn btn-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                    <i class="fas fa-home fa-lg text-white"></i>
                </a>
                <h1 class="font-weight-bold text-dark mb-0 mx-auto" style="font-size: 2rem;">
                    Data CRM
                </h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter Laporan</h3>
                </div>
                <div class="card-body p-4">
                    <form action="<?= site_url('Dashboard_new/visual_data_crm') ?>" method="post" class="row g-3 align-items-end">

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-secondary">Pilih Tipe Filter:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_type_range" value="range" 
                                    <?= ($filter_type == 'range') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="filter_type_range">Range Bulan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_type_quarter" value="quarter"
                                    <?= ($filter_type == 'quarter') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="filter_type_quarter">Triwulan (Quarter)</label>
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="row g-3" id="range_filter_inputs">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-bold text-secondary"><i class="fas fa-calendar-alt mr-2"></i>Dari Bulan</label>
                                    <input type="month" name="start_date" id="start_date" class="form-control" style="border-radius: 10px;" value="<?= $selected_start; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-bold text-secondary"><i class="fas fa-calendar-alt mr-2"></i>Sampai Bulan</label>
                                    <input type="month" name="end_date" id="end_date" class="form-control" style="border-radius: 10px;" value="<?= $selected_end; ?>">
                                </div>
                            </div>
                            <div class="row g-3" id="quarter_filter_inputs">
                                <div class="col-md-6">
                                    <label for="quarter" class="form-label fw-bold text-secondary"><i class="fas fa-calendar-alt mr-2"></i>Triwulan</label>
                                    <select name="quarter" id="quarter" class="form-control form-select" style="border-radius: 10px;">
                                        <option value="Q1" <?= ($selected_quarter == 'Q1') ? 'selected' : ''; ?>>Q1 (Januari - Maret)</option>
                                        <option value="Q2" <?= ($selected_quarter == 'Q2') ? 'selected' : ''; ?>>Q2 (April - Juni)</option>
                                        <option value="Q3" <?= ($selected_quarter == 'Q3') ? 'selected' : ''; ?>>Q3 (Juli - September)</option>
                                        <option value="Q4" <?= ($selected_quarter == 'Q4') ? 'selected' : ''; ?>>Q4 (Oktober - Desember)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quarter_year" class="form-label fw-bold text-secondary"><i class="fas fa-calendar mr-2"></i>Tahun</label>
                                    <select name="quarter_year" id="quarter_year" class="form-control form-select" style="border-radius: 10px;">
                                        <?php for ($i = date('Y'); $i >= date('Y') - 7; $i--): ?>
                                            <option value="<?= $i; ?>" <?= ($selected_quarter_year == $i) ? 'selected' : ''; ?>><?= $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px; height: 45px;">
                                <i class="fas fa-search mr-2"></i>Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold" style="color: #2c3e50;"><i class="fas fa-map-marked-alt mr-2" style="color: #f39c12;"></i>Laporan Performa per Area</h3>
                        <a id="resetAreaFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-no-horizontal-scroll" style="max-height: 500px;">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="border-top: none;">Area</th>
                                    <th style="border-top: none;" class="text-center">Total Aktual Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody id="areaPerformanceBody">
                                <?php if (empty($area_performance_data)): ?>
                                    <tr><td colspan="2" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data</td></tr>
                                <?php else: ?>
                                    <?php foreach ($area_performance_data as $row): ?>
                                        <tr class="area-row" data-area-id="<?= $row['region_id']; ?>" data-area-name="<?= htmlspecialchars($row['region_name']); ?>" style="cursor: pointer; transition: all 0.3s ease;">
                                            <td class="align-middle">
                                                <strong style="color: #2c3e50;"><i class="fas fa-map-marker-alt mr-2" style="color: #3498db;"></i><?= htmlspecialchars($row['region_name']); ?></strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info px-3 py-2" style="font-size: 14px;"><?= number_format($row['total_aktual']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
               <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold" style="color: #2c3e50;"><i class="fas fa-users mr-2" style="color: #e74c3c;"></i>Laporan Performa Surveyor</h3>
                            <a id="resetSurveyorFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua Area</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width: 60px; border-top: none;" class="text-center">No.</th>
                                        <th style="border-top: none;">Surveyor</th>
                                        <th style="border-top: none;" class="text-center">Aktual Kunjungan</th>
                                    </tr>
                                </thead>
                                <tbody id="surveyorPerformanceBody">
                                    <?php if (empty($performance_data)): ?>
                                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data</td></tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach ($performance_data as $row): ?>
                                            <tr class="surveyor-row" data-user-id="<?= $row['surveyor_id']; ?>" data-username="<?= htmlspecialchars($row['surveyor_name']); ?>" style="cursor: pointer; transition: all 0.3s ease;">
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-light rounded-circle"><?= $no++; ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <strong><?= htmlspecialchars($row['surveyor_name']); ?></strong>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-info px-3 py-2" style="font-size: 14px;"><?= number_format($row['aktual']); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold" style="color: #2c3e50;"><i class="fas fa-chart-pie mr-2" style="color: #9b59b6;"></i>Komposisi Visit</h3>
                    <a id="resetCompositionFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive table-no-horizontal-scroll" style="max-height: 500px;">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="border-top: none;">Komoditas / Tujuan</th>
                                <th style="width: 100px; border-top: none;" class="text-right">Persentase</th>
                            </tr>
                        </thead>
                        <tbody id="compositionVisitBody">
                            <?php if(empty($visit_breakdown_data)): ?>
                                <tr><td colspan="2" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data</td></tr>
                            <?php else: ?>
                                <?php 
                                $colors = ['#1e3c72', '#2a5298', '#3498db', '#5dade2', '#21618c', '#1a5490', '#154360'];
                                $index = 0;
                                foreach ($visit_breakdown_data as $row): 
                                    $color = $colors[$index % count($colors)];
                                ?>
                                    <tr class="composition-row" data-kategori="<?= htmlspecialchars($row['kategori']); ?>" style="cursor: pointer; transition: all 0.3s ease;">
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div style="width: 12px; height: 12px; border-radius: 50%; background-color: <?= $color; ?>; margin-right: 12px;"></div>
                                                <?= htmlspecialchars($row['kategori']); ?>
                                            </div>
                                        </td>
                                        <td class="text-right align-middle">
                                            <strong style="color: <?= $color; ?>; font-size: 16px;"><?= round($row['persentase'], 2); ?>%</strong>
                                        </td>
                                    </tr>
                                <?php 
                                    $index++;
                                endforeach; 
                                ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4">
                 <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title font-weight-bold mb-0" style="color: #2c3e50;"><i class="fas fa-clipboard-list mr-2" style="color: #16a085;"></i>Detail Log Kunjungan</h4>
                        <a id="resetLogFilter" class="btn btn-sm btn-outline-secondary ml-2" style="display:none; border-radius: 20px; font-size: 0.8rem; vertical-align: middle;"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua</a>
                        <small class="text-muted">Klik pada baris untuk melihat detail lengkap</small>
                    </div>
                    <div class="col-md-6">
                        <div class="search-box">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-search text-muted"></i></span></div>
                                <input type="text" class="form-control border-left-0" id="searchInput" placeholder="Cari data..." style="border-radius: 0 10px 10px 0; border-left: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                    <table class="table mb-0" id="dataTableVisitDetails">
                        <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th style="border-top: none; width: 30px;"></th>
                                <th style="border-top: none; width: 120px;">Username</th>
                                <th style="border-top: none; width: 130px;">Jenis Visit</th>
                                <th style="border-top: none; width: 100px;">Nama Customer</th>
                                <th style="border-top: none; width: 100px;">Kapasitas</th>
                                <th style="border-top: none; width: 100px;">Waktu</th>
                                <th style="border-top: none; width: 120px;">Pakan</th>
                                <th style="border-top: none; width: 100px;" class="text-center">Lokasi</th>
                            </tr>
                        </thead>
                       <tbody id="visitTableBody">
                        <?php if (!empty($visit_details_table)): ?>
                            <?php foreach ($visit_details_table as $visit): ?>
                                <tr class="visit-row" 
                                    data-username="<?php echo htmlspecialchars($visit['username'], ENT_QUOTES, 'UTF-8'); ?>" 
                                    data-jenis-visit="<?php echo htmlspecialchars($visit['kategori_visit'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-area-id="<?php echo htmlspecialchars($visit['master_area_id'], ENT_QUOTES, 'UTF-8'); ?>" style="transition: all 0.3s ease;">

                                    <td class="text-center visit-cell-icon"><i class="fas fa-chevron-right expand-icon"></i></td>
                                    <td class="visit-cell"><span class="badge badge-primary" style="border-radius: 20px; padding: 6px 12px;"><?php echo htmlspecialchars($visit['username'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td class="visit-cell"><?php echo htmlspecialchars($visit['kategori_visit'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="visit-cell wide"><strong><?php echo htmlspecialchars($visit['nama_customer'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td class="visit-cell"><?php echo htmlspecialchars($visit['kapasitas'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="visit-cell"><small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($visit['waktu_kunjungan'])), ENT_QUOTES, 'UTF-8'); ?></small></td>
                                    <td class="visit-cell"><?php echo htmlspecialchars($visit['pakan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center visit-cell">
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $visit['latitude']; ?>,<?php echo $visit['longitude']; ?>" 
                                        target="_blank" 
                                        class="btn btn-sm btn-outline-primary rounded-pill"
                                        onclick="event.stopPropagation();">
                                            <i class="fas fa-map-marker-alt mr-1"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data kunjungan untuk periode ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<style>
    /* Modern Enhancements */
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
        border-color: #2a5298;
        box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(42, 82, 152, 0.4);
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Scrollbar Styling */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #2a5298;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #1e3c72;
    }
    
    /* Hide horizontal scrollbar only for specific tables */
    .table-no-horizontal-scroll {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }
    
    .table-no-horizontal-scroll::-webkit-scrollbar {
        width: 8px;
        height: 0px;
    }
    
    .table-no-horizontal-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-no-horizontal-scroll::-webkit-scrollbar-thumb {
        background: #2a5298;
        border-radius: 10px;
    }
    
    .table-no-horizontal-scroll::-webkit-scrollbar-thumb:hover {
        background: #1e3c72;
    }
    
    /* Expandable Row Styles - MODIFIED FOR VERTICAL EXPANSION */
    .visit-row {
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .visit-row:hover {
        background-color: #f8f9fa;
    }
    
    .visit-row.expanded {
        background-color: #e3f2fd;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    /* Cell styling untuk expand vertikal */
    .visit-cell {
        padding: 12px;
        vertical-align: top;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
        line-height: 1.5;
    }
    
    /* Lebar kolom yang lebih besar */
    .visit-cell.wide {
        max-width: 100px;
    }
    
    .visit-cell.extra-wide {
        max-width: 100px;
    }
    
    /* Cell icon tidak berubah */
    .visit-cell-icon {
        padding: 12px;
        vertical-align: middle;
        width: 30px;
    }
    
    /* Saat expanded - text wrap dengan lebar tetap */
    .visit-row.expanded .visit-cell {
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 16px 12px;
        vertical-align: top;
    }
    
    /* Icon expand */
    .expand-icon {
        transition: transform 0.3s ease;
        color: #2a5298;
        font-size: 12px;
    }
    
    .visit-row.expanded .expand-icon {
        transform: rotate(90deg);
    }
    
    .badge-primary {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
    }
    
    /* Pastikan table layout fixed untuk konsistensi lebar kolom */
    #dataTableVisitDetails {
        table-layout: fixed;
        width: 100%;
    }

    /* [BARU] Aturan highlight abu-abu untuk semua tabel */
    
    #areaPerformanceBody tr.row-selected,
    #areaPerformanceBody tr.row-selected td {
        background-color: #d6d8db !important; /* Abu-abu lebih gelap untuk Area */
    }
    
    #areaPerformanceBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    #surveyorPerformanceBody tr.row-selected,
    #surveyorPerformanceBody tr.row-selected td {
        background-color: #d6d8db !important; /* Abu-abu lebih gelap untuk Surveyor */
    }
    
    #surveyorPerformanceBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    #compositionVisitBody tr.row-selected,
    #compositionVisitBody tr.row-selected td {
        background-color: #d6d8db !important; /* Abu-abu lebih gelap untuk Komposisi */
    }
    
    #compositionVisitBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    /* Hover effect - background abu-abu tetap dipertahankan */
    #areaPerformanceBody tr.row-selected:hover,
    #areaPerformanceBody tr.row-selected:hover td,
    #surveyorPerformanceBody tr.row-selected:hover,
    #surveyorPerformanceBody tr.row-selected:hover td,
    #compositionVisitBody tr.row-selected:hover,
    #compositionVisitBody tr.row-selected:hover td {
        background-color: #ced4da !important; /* Abu-abu lebih gelap saat hover */
    }

    /* --- CSS UNTUK MEMPERPENDEK BARIS TABEL --- */

    /* 1. Atur padding sel (td) untuk AREA */
    #areaPerformanceBody td {
        padding-top: 5px !important;    
        padding-bottom: 5px !important; 
        vertical-align: middle; 
    }
    #areaPerformanceBody .py-2 {
        padding-top: 0.25rem !important; 
        padding-bottom: 0.25rem !important; 
        font-size: 14px !important; 
    }

    /* 2. Atur padding sel (td) untuk SURVEYOR */
    #surveyorPerformanceBody td {
        padding-top: 5px !important;    
        padding-bottom: 5px !important; 
        vertical-align: middle; 
    }
    #surveyorPerformanceBody .py-2 {
        padding-top: 0.25rem !important; 
        padding-bottom: 0.25rem !important; 
        font-size: 14px !important; 
    }

    /* 3. Badge "No." (Tetap 13px) */
    #surveyorPerformanceBody .badge.rounded-circle {
        height: auto !important;       
        width: auto !important;        
        line-height: normal !important;  
        
        padding: 0.3em 0.65em !important; 
        font-size: 13px !important; 
        
        border-radius: 50rem !important; 
    }
    
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- FUNGSI EXPANDABLE ROW (Sama) ---
    const logTableBody = document.getElementById('visitTableBody');
    if (logTableBody) {
        logTableBody.addEventListener('click', function(e) {
            if (e.target.closest('.btn-outline-primary')) return;
            const row = e.target.closest('.visit-row');
            if (!row) return;
            if (row.classList.contains('expanded')) {
                row.classList.remove('expanded');
            } else {
                logTableBody.querySelectorAll('.visit-row').forEach(r => r.classList.remove('expanded'));
                row.classList.add('expanded');
            }
        });
    }

    // --- FUNGSI TOGGLE FILTER (Sama) ---
    const rangeInputs = document.getElementById('range_filter_inputs');
    const quarterInputs = document.getElementById('quarter_filter_inputs');
    const filterRadios = document.querySelectorAll('input[name="filter_type"]');
    function toggleFilterInputs() {
        const selectedType = document.querySelector('input[name="filter_type"]:checked').value;
        rangeInputs.style.display = (selectedType === 'range') ? 'flex' : 'none';
        quarterInputs.style.display = (selectedType === 'quarter') ? 'flex' : 'none';
    }
    filterRadios.forEach(radio => radio.addEventListener('change', toggleFilterInputs));
    toggleFilterInputs();
    
    // --- FUNGSI SEARCH (Sama) ---
    // Fungsi ini dinamis dan akan otomatis menyesuaikan dengan jumlah kolom (colspan)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#dataTableVisitDetails tbody tr.visit-row');
            const noDataRow = document.querySelector('#visitTableBody .no-data-filter');
            if (noDataRow) noDataRow.remove();

            let hasVisibleRows = false;
            tableRows.forEach(function(row) {
                if (row.style.display !== 'none') { 
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(searchValue)) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                        row.classList.remove('expanded');
                    }
                }
            });
            if (!hasVisibleRows && tableRows.length > 0) {
                // 'cells.length' membuat colspan dinamis (8 kolom)
                const colspan = tableRows[0].cells.length; 
                logTableBody.insertAdjacentHTML('beforeend', `
                    <tr class="no-data-filter"><td colspan="${colspan}" class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                    Tidak ada data log yang cocok dengan pencarian "${escapeHTML(searchValue)}"
                    </td></tr>`);
            }
        });
    }

    // --- PENGATURAN UMUM UNTUK AJAX ---
    // [DIUBAH] Site URL sekarang menunjuk ke fungsi CRM
    const siteUrl = "<?= site_url('Dashboard_new'); ?>";
    const currentStartDate = "<?= $js_start_date; ?>";
    const currentEndDate = "<?= $js_end_date; ?>";
    const csrfTokenName = "<?= $this->security->get_csrf_token_name(); ?>";
    let csrfTokenHash = "<?= $this->security->get_csrf_hash(); ?>";

    // --- ELEMEN TABEL & TOMBOL (Sama) ---
    const areaTableBody = document.getElementById('areaPerformanceBody');
    const surveyorTableBody = document.getElementById('surveyorPerformanceBody');
    const compositionTableBody = document.getElementById('compositionVisitBody');
    const resetAreaBtn = document.getElementById('resetAreaFilter');
    const resetSurveyorBtn = document.getElementById('resetSurveyorFilter');
    const resetCompBtn = document.getElementById('resetCompositionFilter');
    const resetLogBtn = document.getElementById('resetLogFilter');
    
    // --- [DIUBAH] EVENT: Klik baris AREA ---
    // Memanggil AJAX baru dan me-render tabel baru
    if (areaTableBody) {
        areaTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.area-row');
            if (!row) return;

            const areaId = row.dataset.areaId;
            const areaName = row.dataset.areaName;

            areaTableBody.querySelectorAll('.area-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');

            // Reset tabel-tabel di bawahnya
            // [DIUBAH] Colspan untuk surveyor adalah 3
            if(surveyorTableBody) surveyorTableBody.innerHTML = `<tr><td colspan="3" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Memuat surveyor...</p></td></tr>`;
            if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Memuat komposisi...</p></td></tr>`;
            
            // Panggil filter master log
            if(logTableBody) updateLogFilter(); 

            const formData = new URLSearchParams();
            formData.append('area_id', areaId);
            formData.append('start_date', currentStartDate);
            formData.append('end_date', currentEndDate);
            formData.append(csrfTokenName, csrfTokenHash);

            // [DIUBAH] Panggil AJAX BARU
            fetch(`${siteUrl}/get_crm_surveyors_for_area_ajax`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => {
                csrfTokenHash = response.headers.get('X-CSRF-TOKEN') || csrfTokenHash;
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // [DIUBAH] Panggil updateSurveyorTable yang BARU (disederhanakan)
                    updateSurveyorTable(data.surveyor_data, areaName);
                    // Panggil updateCompositionTable (tetap sama)
                    updateCompositionTable(data.composition_data); 
                    
                    if(resetSurveyorBtn) resetSurveyorBtn.style.display = 'inline-block';
                    if(resetCompBtn) resetCompBtn.style.display = 'none';
                    if(resetLogBtn) resetLogBtn.style.display = 'none';
                } else {
                    // [DIUBAH] Colspan 3
                    if(surveyorTableBody) surveyorTableBody.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-danger">Gagal memuat data.</td></tr>`;
                    if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-danger">Gagal memuat data.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // [DIUBAH] Colspan 3
                if(surveyorTableBody) surveyorTableBody.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-danger">Terjadi kesalahan.</td></tr>`;
                if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-danger">Terjadi kesalahan.</td></tr>`;
            });
        });
    }

    // --- [DIUBAH] EVENT: Klik baris SURVEYOR ---
    // Memanggil AJAX baru
    if (surveyorTableBody) {
        surveyorTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.surveyor-row');
            if (!row) return;

            const userId = row.dataset.userId;
            const username = row.dataset.username;
            
            surveyorTableBody.querySelectorAll('.surveyor-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');

            if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Memuat komposisi...</p></td></tr>`;
            
            // Panggil filter master log
            updateLogFilter();
            
            const formData = new URLSearchParams();
            formData.append('user_id', userId);
            formData.append('start_date', currentStartDate);
            formData.append('end_date', currentEndDate);
            formData.append(csrfTokenName, csrfTokenHash);

            // [DIUBAH] Panggil AJAX BARU
            fetch(`${siteUrl}/get_crm_data_for_surveyor_ajax`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => {
                csrfTokenHash = response.headers.get('X-CSRF-TOKEN') || csrfTokenHash;
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Fungsi ini sama, tidak perlu diubah
                    updateCompositionTable(data.composition_data);
                    if(resetCompBtn) resetCompBtn.style.display = 'inline-block';
                    if(resetLogBtn) resetLogBtn.style.display = 'inline-block';
                } else {
                    if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-danger">Gagal memuat data.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-danger">Terjadi kesalahan.</td></tr>`;
            });
        });
    }

    // --- [DIUBAH] EVENT: Klik baris KOMPOSISI VISIT ---
    // Logikanya sama, tapi filter log-nya disederhanakan
    if (compositionTableBody) {
        compositionTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.composition-row');
            if (!row) return;

            const kategori = row.dataset.kategori;
            if (!kategori) return;

            compositionTableBody.querySelectorAll('.composition-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');

            // Panggil filter master log
            updateLogFilter(); 

            if(resetCompBtn) resetCompBtn.style.display = 'inline-block';
            if(resetLogBtn) resetLogBtn.style.display = 'inline-block';
        });
    }

    // --- FUNGSI TOMBOL RESET (Sama) ---
    const resetAllFilters = (e) => {
        e.preventDefault();
        location.reload(); 
    };
    if(resetAreaBtn) resetAreaBtn.addEventListener('click', resetAllFilters);
    if(resetSurveyorBtn) resetSurveyorBtn.addEventListener('click', resetAllFilters);
    if(resetCompBtn) resetCompBtn.addEventListener('click', resetAllFilters);
    if(resetLogBtn) resetLogBtn.addEventListener('click', resetAllFilters);
    
    
    // --- [DIUBAH] (HELPER) Update Tabel Surveyor ---
    // Disederhanakan untuk 3 kolom
    function updateSurveyorTable(data, areaName) {
        if (!surveyorTableBody) return;
        surveyorTableBody.innerHTML = ''; 

        if (!data || data.length === 0) {
             // [DIUBAH] Colspan 3
             surveyorTableBody.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data surveyor.</td></tr>`;
             return;
        }

        let no = 1;
        data.forEach(row => {
            const tr = `
                <tr class="surveyor-row" 
                    data-user-id="${row.surveyor_id}" 
                    data-username="${escapeHTML(row.surveyor_name)}" 
                    style="cursor: pointer; transition: all 0.3s ease;">
                    
                    <td class="text-center align-middle">
                        <span class="badge badge-light rounded-circle">${no++}</span>
                    </td>
                    <td class="align-middle">
                        <strong>${escapeHTML(row.surveyor_name)}</strong>
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-info px-3 py-2" style="font-size: 14px;">
                            ${new Intl.NumberFormat('id-ID').format(row.aktual)}
                        </span>
                    </td>
                </tr>
            `;
            surveyorTableBody.insertAdjacentHTML('beforeend', tr);
        });
    }

    // --- (HELPER) Update Tabel Komposisi (Sama) ---
    // Fungsi ini tidak perlu diubah, sudah generik.
    function updateCompositionTable(data, reset = false) {
        if (!compositionTableBody) return;
        compositionTableBody.innerHTML = ''; 
        
        const originalNoData = document.querySelector('.original-no-data-composition');
        if (originalNoData) originalNoData.style.display = 'none';

        if (reset) {
             compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-muted"><i class="fas fa-hand-pointer fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Pilih surveyor.</td></tr>`;
             return;
        }
        if (!data || data.length === 0) {
             compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>Tidak ada data.</td></tr>`;
             return;
        }

        const colors = ['#1e3c72', '#2a5298', '#3498db', '#5dade2', '#21618c', '#1a5490', '#154360'];
        let index = 0;

        data.forEach(row => {
            const color = colors[index % colors.length];
            const percent = parseFloat(row.persentase).toFixed(2);
            const tr = `
                <tr class="composition-row" data-kategori="${escapeHTML(row.kategori)}" style="cursor: pointer; transition: all 0.3s ease;">
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: ${color}; margin-right: 12px;"></div>
                            ${escapeHTML(row.kategori)}
                        </div>
                    </td>
                    <td class="text-right align-middle">
                        <strong style="color: ${color}; font-size: 16px;">${percent}%</strong>
                    </td>
                </tr>
            `;
            compositionTableBody.insertAdjacentHTML('beforeend', tr);
            index++;
        });
    }

    // --- [DIUBAH] (HELPER) Filter Log Master ---
    // Logika filter komposisi disederhanakan
    function updateLogFilter() {
        if (!logTableBody) return;

        // 1. Dapatkan semua filter yang sedang aktif
        const activeAreaRow = document.querySelector('#areaPerformanceBody tr.row-selected');
        const activeSurveyorRow = document.querySelector('#surveyorPerformanceBody tr.row-selected');
        const activeCompositionRow = document.querySelector('#compositionVisitBody tr.row-selected');

        const activeAreaId = activeAreaRow?.dataset.areaId;
        const activeUsername = activeSurveyorRow?.dataset.username;
        const activeKategori = activeCompositionRow?.dataset.kategori;

        const allLogRows = logTableBody.querySelectorAll('tr.visit-row');
        let hasData = false;

        // 2. Hapus pesan 'no data' lama
        const existingNoDataRow = logTableBody.querySelector('.no-data-filter');
        if (existingNoDataRow) existingNoDataRow.remove();
        
        const originalNoData = logTableBody.querySelector('.original-no-data');
        if (originalNoData) originalNoData.style.display = 'none';

        // 3. Loop setiap baris log dan terapkan SEMUA filter
        allLogRows.forEach(row => {
            const rowAreaId = row.dataset.areaId;
            const rowUsername = row.dataset.username;
            const rowJenisVisit = row.dataset.jenisVisit;

            let show = true; 

            // Filter 1: Area
            if (activeAreaId && rowAreaId != activeAreaId) {
                show = false;
            }

            // Filter 2: Surveyor
            if (show && activeUsername && rowUsername !== activeUsername) {
                show = false;
            }

            // Filter 3: Komposisi
            if (show && activeKategori) {
                // [LOGIKA BARU YANG DISEDERHANAKAN]
                // Langsung bandingkan kategori yang diklik dengan data-jenis-visit
                let showKategori = (rowJenisVisit === activeKategori);
                
                if (!showKategori) {
                    show = false;
                }
            }

            // 4. Terapkan hasil filter
            if (show) {
                row.style.display = '';
                hasData = true;
            } else {
                row.style.display = 'none';
                row.classList.remove('expanded');
            }
        });

        // 5. Tampilkan pesan 'no data' jika tidak ada baris yang lolos
        if (!hasData && allLogRows.length > 0) {
            let message = 'Tidak ada data log untuk filter yang dipilih.';
            
            if (activeKategori) message = `Tidak ada data log untuk kategori "${escapeHTML(activeKategori)}"`;
            else if (activeUsername) message = `Tidak ada data log untuk surveyor "${escapeHTML(activeUsername)}".`;
            else if (activeAreaId && activeAreaRow) message = `Tidak ada data log untuk area "${escapeHTML(activeAreaRow.dataset.areaName)}".`;

            // 'cells.length' membuat colspan dinamis (8 kolom)
            const colspan = allLogRows[0].cells.length;
            logTableBody.insertAdjacentHTML('beforeend', `
                <tr class="no-data-filter">
                    <td colspan="${colspan}" class="text-center py-5 text-muted">
                        <i class="fas fa-filter fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                        ${message}
                    </td>
                </tr>
            `);
        } else if (originalNoData && !activeAreaId && !activeUsername && !activeKategori) {
            originalNoData.style.display = '';
        }
    }
    
    // --- [PERBAIKAN BUG] HELPER XSS ---
    // Fungsi ini diambil dari file lama Anda karena lebih aman.
    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString().replace(/[&<>"']/g, function(m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[m];
        });
    }

    // --- Inisialisasi (Sama) ---
    const originalLogNoData = document.querySelector('#visitTableBody tr:not(.visit-row)');
    if (originalLogNoData) originalLogNoData.classList.add('original-no-data');
    
    const originalCompositionNoData = document.querySelector('#compositionVisitBody tr:not([style*="transition"])');
    if (originalCompositionNoData) originalCompositionNoData.classList.add('original-no-data-composition');

    if(logTableBody) updateLogFilter();
    
    if(resetSurveyorBtn) resetSurveyorBtn.style.display = 'none';
    if(resetCompBtn) resetCompBtn.style.display = 'none';
    if(resetLogBtn) resetLogBtn.style.display = 'none';
    
    <?php if (!isset($user['group_user']) || ($user['group_user'] !== 'surveyor' && $user['group_user'] !== 'koordinator')): ?>
        if(resetAreaBtn) resetAreaBtn.style.display = 'inline-block';
    <?php else: ?>
        if(resetAreaBtn) resetAreaBtn.style.display = 'none';
    <?php endif; ?>
    
});
</script>