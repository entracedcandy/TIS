<div class="container-fluid">
    <section class="content-header mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center p-3 bg-white shadow-sm" style="border-radius: 2rem;">
                <a href="<?= site_url('Dashboard_new/index') ?>" class="btn btn-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                    <i class="fas fa-home fa-lg text-white"></i>
                </a>
                <h1 class="font-weight-bold text-dark mb-0 mx-auto" style="font-size: 2rem;">
                    Data Kunjungan
                </h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-filter mr-2"></i>Filter Laporan
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="<?= site_url('Dashboard_new/visual_data_kunjungan') ?>" method="post" class="row g-3 align-items-end">

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
                                    <label for="start_date" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Dari Bulan
                                    </label>
                                    <input type="month" name="start_date" id="start_date" class="form-control" 
                                        style="border-radius: 10px;" value="<?= $selected_start; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Sampai Bulan
                                    </label>
                                    <input type="month" name="end_date" id="end_date" class="form-control" 
                                        style="border-radius: 10px;" value="<?= $selected_end; ?>">
                                </div>
                            </div>

                            <div class="row g-3" id="quarter_filter_inputs">
                                <div class="col-md-6">
                                    <label for="quarter" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Triwulan
                                    </label>
                                    <select name="quarter" id="quarter" class="form-control form-select" style="border-radius: 10px;">
                                        <option value="Q1" <?= ($selected_quarter == 'Q1') ? 'selected' : ''; ?>>Q1 (Januari - Maret)</option>
                                        <option value="Q2" <?= ($selected_quarter == 'Q2') ? 'selected' : ''; ?>>Q2 (April - Juni)</option>
                                        <option value="Q3" <?= ($selected_quarter == 'Q3') ? 'selected' : ''; ?>>Q3 (Juli - September)</option>
                                        <option value="Q4" <?= ($selected_quarter == 'Q4') ? 'selected' : ''; ?>>Q4 (Oktober - Desember)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quarter_year" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar mr-2"></i>Tahun
                                    </label>
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
                        <h3 class="card-title font-weight-bold" style="color: #2c3e50;">
                            <i class="fas fa-map-marked-alt mr-2" style="color: #f39c12;"></i>Laporan Performa per Area
                        </h3>
                        <a id="resetAreaFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;">
                            <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-no-horizontal-scroll" style="max-height: 500px;"> 
                        <table class="table table-sm table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 35%; border-top: none;">Area</th>
                                    <th style="width: 25%; border-top: none;" class="text-center">Total Target</th>
                                    <th style="width: 25%; border-top: none;" class="text-center">Total Aktual</th>
                                    <th style="width: 15%; border-top: none;" class="text-center">Pencapaian</th>
                                </tr>
                            </thead>
                            <tbody id="areaPerformanceBody">
                                <?php if (empty($area_performance_data)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                            Tidak ada data untuk ditampilkan
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($area_performance_data as $row): ?>
                                        <tr class="area-row" 
                                            data-area-id="<?= $row['master_area_id']; ?>" 
                                            data-area-name="<?= htmlspecialchars($row['nama_area']); ?>" 
                                            style="cursor: pointer; transition: all 0.3s ease;">
                                            <td class="align-middle">
                                                <strong style="color: #2c3e50;">
                                                    <i class="fas fa-map-marker-alt mr-2 d-none d-sm-inline d-print-inline" style="color: #3498db;"></i>
                                                    <?= htmlspecialchars($row['nama_area']); ?>
                                                </strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light px-3 py-2" style="font-size: 14px;">
                                                    <?= number_format($row['total_target']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info px-3 py-2" style="font-size: 14px;">
                                                    <?= number_format($row['total_aktual']); ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center" style="font-weight: bold; color: #000000ff;">
                                                <?= round($row['achievement_percent'], 1); ?>%
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                   <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title font-weight-bold" style="color: #2c3e50;">
                                    <i class="fas fa-users mr-2" style="color: #e74c3c;"></i>Laporan Performa Surveyor
                                </h3>
                                <a id="resetSurveyorFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;">
                                    <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua Area
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px;">
                                <table class="table table-hover mb-0" style="font-size:11pt;">
                                    <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th style="width: 60px; border-top: none;" class="text-center">No.</th>
                                            <th style="border-top: none;">Surveyor</th>
                                            <th style="border-top: none;" class="text-center">Target Total</th>
                                            <th style="border-top: none;" class="text-center">Aktual Total</th>
                                            <th style="border-top: none;" class="text-center">Pencapaian Total</th>
                                            <th style="border-top: none;" class="text-center">Target VIP</th>
                                            <th style="border-top: none;" class="text-center">Aktual VIP</th>
                                            <th style="border-top: none;" class="text-center">Pencapaian VIP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="surveyorPerformanceBody">
                                        <?php if (empty($performance_data)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                                    Tidak ada data untuk ditampilkan
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            
                                            <?php 
                                            $no = 1; 
                                            foreach ($performance_data as $row): 
                                                $percent_num = (float)$row['achievement_percent'];
                                                $percent_num_vip = (float)$row['achievement_percent_vip'];
                                                $color_standar = ($percent_num >= 100) ? '#28a745' : '#ffc107'; 
                                                $color_vip = ($percent_num_vip >= 100) ? '#28a745' : '#dc3545';
                                            ?>
                                                <tr class="surveyor-row" 
                                                    data-user-id="<?= $row['id_user']; ?>" 
                                                    data-username="<?= htmlspecialchars($row['surveyor_name']); ?>" 
                                                    style="cursor: pointer; transition: all 0.3s ease;">

                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-light rounded-circle">
                                                            <?= $no++; ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <strong><?= htmlspecialchars($row['surveyor_name']); ?></strong>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-light px-3 py-2" style="font-size: 14px;">
                                                            <?= number_format($row['target']); ?>
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-info px-3 py-2" style="font-size: 14px;">
                                                            <?= number_format($row['aktual']); ?>
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="align-middle text-center" style="font-weight: bold; color: <?= $color_standar ?>; font-size: 16px;">
                                                        <?= round($row['achievement_percent'], 1); ?>%
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-danger px-2 py-1" style="font-size: 12px; opacity: 0.8;">
                                                            <i class="fas fa-star fa-xs mr-1"></i><?= number_format($row['target_vip']); ?>
                                                        </span>
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-warning px-2 py-1" style="font-size: 12px; opacity: 0.8;">
                                                            <i class="fas fa-star fa-xs mr-1"></i><?= number_format($row['aktual_vip']); ?>
                                                        </span>
                                                    </td>

                                                    <td class="align-middle text-center" style="font-weight: bold; color: <?= $color_vip ?>; font-size: 13px; opacity: 0.8;">
                                                        <i class="fas fa-star fa-xs mr-1"></i><?= round($row['achievement_percent_vip'], 1); ?>%
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
                        <h3 class="card-title font-weight-bold" style="color: #2c3e50;">
                            <i class="fas fa-chart-pie mr-2" style="color: #9b59b6;"></i>Komposisi Visit
                        </h3>
                        <a id="resetCompositionFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;">
                            <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive table-no-horizontal-scroll" style="max-height: 500px;">
                        <table class="table table-hover mb-0" style="font-size:11pt;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="border-top: none;">Komoditas / Tujuan</th>
                                    <th style="width: 100px; border-top: none;" class="text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody id="compositionVisitBody">
                                <?php if(empty($visit_breakdown_data)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                            Tidak ada data
                                        </td>
                                    </tr>
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
                                                <strong style="color: <?= $color; ?>; font-size: 16px;">
                                                    <?= round($row['persentase'], 2); ?>%
                                                </strong>
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
                        <div class="col-md-4">
                            <h4 class="card-title font-weight-bold mb-0" style="color: #2c3e50;">
                                <i class="fas fa-clipboard-list mr-2" style="color: #16a085;"></i>Detail Log Kunjungan
                            </h4>
                            <a id="resetLogFilter" class="btn btn-sm btn-outline-secondary ml-2" style="display:none; border-radius: 20px; font-size: 0.8rem; vertical-align: middle;">
                                <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                            </a>
                            <small class="text-muted d-block mt-1">Klik pada baris untuk melihat detail lengkap</small>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="vipFilter" class="form-label fw-bold text-secondary mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-star mr-1" style="color: #ffc107;"></i>Filter VIP:
                                </label>
                                <select id="vipFilter" class="form-control form-select" style="border-radius: 10px; font-size: 0.9rem;">
                                    <option value="all">Semua</option>
                                    <option value="Ya">VIP Saja</option>
                                    <option value="Tidak">Non-VIP Saja</option>
                                    <option value="-">Tidak Ada Info VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="search-box mt-3 mt-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" id="searchInput" 
                                        placeholder="Cari data pelanggan, area..." 
                                        style="border-radius: 0 10px 10px 0; border-left: none;"> 
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
                                    <th style="border-top: none; width: 80px;" class="text-center">VIP</th>
                                    <th style="border-top: none; width: 100px;">Kapasitas</th>
                                    <th style="border-top: none; width: 100px;">Waktu</th>
                                    <th style="border-top: none; width: 100px;">Tujuan</th>
                                    <th style="border-top: none; width: 120px;">Kasus</th>
                                    <th style="border-top: none; width: 120px;">Pakan</th>
                                    <th style="border-top: none; width: 100px;">Alamat</th>
                                    <th style="border-top: none; width: 100px;" class="text-center">Lokasi</th>
                                    <th style="border-top: none; width: 150px;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="visitTableBody">
                                <tr>
                                    <td colspan="13" class="text-center py-5">
                                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                        <p class="mt-2 text-muted">Memuat data kunjungan...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
    
    /* Highlight abu-abu untuk semua tabel */
    #areaPerformanceBody tr.row-selected,
    #areaPerformanceBody tr.row-selected td {
        background-color: #d6d8db !important;
    }
    
    #areaPerformanceBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    #surveyorPerformanceBody tr.row-selected,
    #surveyorPerformanceBody tr.row-selected td {
        background-color: #d6d8db !important;
    }
    
    #surveyorPerformanceBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    #compositionVisitBody tr.row-selected,
    #compositionVisitBody tr.row-selected td {
        background-color: #d6d8db !important;
    }
    
    #compositionVisitBody tr.row-selected {
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
        border-left: 4px solid #6c757d !important;
    }
    
    /* Hover effect */
    #areaPerformanceBody tr.row-selected:hover,
    #areaPerformanceBody tr.row-selected:hover td,
    #surveyorPerformanceBody tr.row-selected:hover,
    #surveyorPerformanceBody tr.row-selected:hover td,
    #compositionVisitBody tr.row-selected:hover,
    #compositionVisitBody tr.row-selected:hover td {
        background-color: #ced4da !important;
    }

    /* CSS UNTUK MEMPERPENDEK BARIS TABEL */
    #areaPerformanceBody td,
    #surveyorPerformanceBody td {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
        vertical-align: middle; 
    }

    #areaPerformanceBody .py-2,
    #surveyorPerformanceBody .py-2 {
        padding-top: 0.25rem !important; 
        padding-bottom: 0.25rem !important; 
        font-size: 14px !important; 
    }

    #surveyorPerformanceBody .badge.rounded-circle {
        height: auto !important;        
        width: auto !important;        
        line-height: normal !important; 
        padding: 0.3em 0.65em !important; 
        font-size: 13px !important; 
        border-radius: 50rem !important; 
    }

    /* Expandable Row Styles */
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
    
    .visit-cell {
        padding: 12px;
        vertical-align: top;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
        line-height: 1.5;
    }
    
    .visit-cell.wide, .visit-cell.extra-wide {
        max-width: 100px;
    }
    
    .visit-cell-icon {
        padding: 12px;
        vertical-align: middle;
        width: 30px;
    }
    
    .visit-row.expanded .visit-cell {
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding: 16px 12px;
        vertical-align: top;
    }
    
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
    
    #dataTableVisitDetails {
        table-layout: fixed;
        width: 100%;
    }
    
    /* RESPONSIVE STYLES UNTUK MOBILE */
    @media (max-width: 768px) {
        .card-header .row { flex-direction: column; gap: 0.5rem; }
        .card-header .col-md-6 { width: 100%; text-align: center; }
        .card-header h4 { font-size: 0.9rem; }
        .card-header small { font-size: 0.7rem; }
        
        .col-md-12.mb-3 { margin-bottom: 1rem !important; }
        .col-md-12.mb-3 label.form-label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; }
        .form-check.form-check-inline { display: block !important; margin-bottom: 0.5rem; margin-right: 0 !important; }
        .form-check-input { margin-right: 0.5rem; }
        .form-check-label { font-size: 0.85rem; }
        
        .col-md-10 { width: 100% !important; margin-bottom: 1rem; }
        .col-md-10 .row.g-3 { margin-left: 0; margin-right: 0; }
        .col-md-10 .col-md-6 { width: 100% !important; margin-bottom: 0.75rem; padding-left: 0; padding-right: 0; }
        .col-md-10 label.form-label { font-size: 0.8rem; margin-bottom: 0.25rem; }
        .col-md-10 .form-control, .col-md-10 .form-select { font-size: 0.85rem; padding: 0.5rem; }
        
        .col-md-2 { width: 100% !important; padding-left: 0; padding-right: 0; }
        .col-md-2 .btn { width: 100% !important; font-size: 0.85rem; padding: 0.6rem; }
        
        #dataTableVisitDetails th, #dataTableVisitDetails td { font-size: 0.6rem !important; padding: 3px 2px !important; line-height: 1.2; }
        #dataTableVisitDetails thead th { font-size: 0.55rem !important; padding: 4px 2px !important; font-weight: 600; }
        .expand-icon { font-size: 8px !important; }
        .visit-cell-icon { padding: 3px !important; width: 20px !important; }
        #dataTableVisitDetails .badge-primary { font-size: 0.55rem !important; padding: 2px 4px !important; border-radius: 10px; }
        #dataTableVisitDetails strong { font-size: 0.6rem !important; }
        #dataTableVisitDetails small { font-size: 0.5rem !important; }
        #dataTableVisitDetails .far.fa-clock { font-size: 0.5rem; margin-right: 2px; }
        #dataTableVisitDetails .btn-sm { font-size: 0.5rem !important; padding: 2px 4px !important; }
        #dataTableVisitDetails .btn-sm i { font-size: 0.5rem; margin-right: 1px; }
        
        #dataTableVisitDetails { table-layout: auto !important; }
        
        #dataTableVisitDetails thead th:nth-child(1), #dataTableVisitDetails tbody td:nth-child(1) { width: 25px !important; min-width: 25px; }
        #dataTableVisitDetails thead th:nth-child(2), #dataTableVisitDetails tbody td:nth-child(2) { width: 60px !important; min-width: 60px; }
        #dataTableVisitDetails thead th:nth-child(3), #dataTableVisitDetails tbody td:nth-child(3) { width: 70px !important; min-width: 70px; }
        #dataTableVisitDetails thead th:nth-child(4), #dataTableVisitDetails tbody td:nth-child(4) { width: 80px !important; min-width: 80px; }
        #dataTableVisitDetails thead th:nth-child(5), #dataTableVisitDetails tbody td:nth-child(5) { width: 35px !important; min-width: 35px; }
        #dataTableVisitDetails thead th:nth-child(6), #dataTableVisitDetails tbody td:nth-child(6) { width: 50px !important; min-width: 50px; }
        #dataTableVisitDetails thead th:nth-child(7), #dataTableVisitDetails tbody td:nth-child(7) { width: 75px !important; min-width: 75px; }
        #dataTableVisitDetails thead th:nth-child(8), #dataTableVisitDetails tbody td:nth-child(8) { width: 70px !important; min-width: 70px; }
        #dataTableVisitDetails thead th:nth-child(9), #dataTableVisitDetails tbody td:nth-child(9) { width: 60px !important; min-width: 60px; }
        #dataTableVisitDetails thead th:nth-child(10), #dataTableVisitDetails tbody td:nth-child(10) { width: 60px !important; min-width: 60px; }
        #dataTableVisitDetails thead th:nth-child(11), #dataTableVisitDetails tbody td:nth-child(11) { width: 80px !important; min-width: 80px; }
        #dataTableVisitDetails thead th:nth-child(13), #dataTableVisitDetails tbody td:nth-child(13) { width: 100px !important; min-width: 100px; }
        
        .card-body .table-responsive { max-height: 400px !important; }
        .visit-row.expanded .visit-cell { padding: 4px 2px !important; font-size: 0.6rem !important; }
        .visit-cell { padding: 3px 2px !important; }
        .visit-cell.wide, .visit-cell.extra-wide { max-width: 80px !important; }
        #resetLogFilter { font-size: 0.65rem; padding: 3px 8px; }
    }
    
    @media (max-width: 480px) {
        #dataTableVisitDetails th, #dataTableVisitDetails td { font-size: 0.55rem !important; padding: 2px 1px !important; }
        #dataTableVisitDetails thead th { font-size: 0.5rem !important; padding: 3px 1px !important; }
        
        #dataTableVisitDetails thead th:nth-child(1), #dataTableVisitDetails tbody td:nth-child(1) { width: 20px !important; }
        #dataTableVisitDetails thead th:nth-child(2), #dataTableVisitDetails tbody td:nth-child(2) { width: 50px !important; }
        #dataTableVisitDetails thead th:nth-child(3), #dataTableVisitDetails tbody td:nth-child(3) { width: 60px !important; }
        #dataTableVisitDetails thead th:nth-child(4), #dataTableVisitDetails tbody td:nth-child(4) { width: 70px !important; }
        #dataTableVisitDetails thead th:nth-child(5), #dataTableVisitDetails tbody td:nth-child(5) { width: 30px !important; }
        #dataTableVisitDetails thead th:nth-child(6), #dataTableVisitDetails tbody td:nth-child(6) { width: 45px !important; }
        #dataTableVisitDetails thead th:nth-child(7), #dataTableVisitDetails tbody td:nth-child(7) { width: 65px !important; }
        #dataTableVisitDetails thead th:nth-child(8), #dataTableVisitDetails tbody td:nth-child(8) { width: 60px !important; }
        #dataTableVisitDetails thead th:nth-child(9), #dataTableVisitDetails tbody td:nth-child(9) { width: 55px !important; }
        #dataTableVisitDetails thead th:nth-child(10), #dataTableVisitDetails tbody td:nth-child(10) { width: 55px !important; }
        #dataTableVisitDetails thead th:nth-child(11), #dataTableVisitDetails tbody td:nth-child(11) { width: 70px !important; }
        #dataTableVisitDetails thead th:nth-child(13), #dataTableVisitDetails tbody td:nth-child(13) { width: 70px !important; }
        
        #dataTableVisitDetails .badge-primary { font-size: 0.5rem !important; padding: 1px 3px !important; }
        #dataTableVisitDetails .btn-sm { font-size: 0.45rem !important; padding: 1px 3px !important; }
        .card-header h4 { font-size: 0.85rem; }
    }

    #vipFilter { border: 1px solid #e0e0e0; transition: all 0.3s ease; }
    #vipFilter:focus { border-color: #ffc107; box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15); }
    #vipFilter option[value="Ya"] { background-color: #fff3cd; }
    #vipFilter option[value="Tidak"] { background-color: #f8f9fa; }
    
    #resetVipFilter { border-color: #ffc107; color: #ffc107; transition: all 0.3s ease; }
    #resetVipFilter:hover { background-color: #ffc107; color: white; transform: translateY(-2px); box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3); }
    #resetVipFilter i { font-size: 0.75rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- SETUP AWAL ---
    const siteUrl = "<?= site_url('Dashboard_new'); ?>";
    const currentStartDate = "<?= $js_start_date ?? ''; ?>";
    const currentEndDate = "<?= $js_end_date ?? ''; ?>";
    const csrfTokenName = "<?= $this->security->get_csrf_token_name(); ?>";
    let csrfTokenHash = "<?= $this->security->get_csrf_hash(); ?>";

    const areaTableBody = document.getElementById('areaPerformanceBody');
    const surveyorTableBody = document.getElementById('surveyorPerformanceBody');
    const compositionTableBody = document.getElementById('compositionVisitBody');
    
    const resetAreaBtn = document.getElementById('resetAreaFilter');
    const resetSurveyorBtn = document.getElementById('resetSurveyorFilter');
    const resetCompBtn = document.getElementById('resetCompositionFilter');

    // SETUP UNTUK TABEL LOG BARU
    const logTableBody = document.getElementById('visitTableBody');
    const vipFilterDropdown = document.getElementById('vipFilter');
    const searchInput = document.getElementById('searchInput');
    const resetLogBtn = document.getElementById('resetLogFilter');

    // AMBIL DATA RAW SEKALI SAJA
    const rawData = <?php echo json_encode($visit_details_table ?? []); ?>;

    // --- VARIABEL UNTUK PAGINASI ---
    let currentFilteredData = [];
    let currentPage = 1;
    const rowsPerPage = 100; // Tampilkan 100 data per halaman (Bisa Anda ubah)

    // Helper Functions
    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        const htmlEntities = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        return String(str).replace(/[&<>"']/g, match => htmlEntities[match]);
    }

    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const d = new Date(dateString);
        if (isNaN(d)) return dateString;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const day = String(d.getDate()).padStart(2, '0');
        const month = months[d.getMonth()];
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        return `${day} ${month} ${year}, ${hours}:${minutes}`;
    }

    // --- TOGGLE FILTER FORM ---
    const rangeInputs = document.getElementById('range_filter_inputs');
    const quarterInputs = document.getElementById('quarter_filter_inputs');
    const filterRadios = document.querySelectorAll('input[name="filter_type"]');

    function toggleFilterInputs() {
        const selectedType = document.querySelector('input[name="filter_type"]:checked').value;
        if (selectedType === 'range') {
            rangeInputs.style.display = 'flex'; quarterInputs.style.display = 'none';
        } else {
            rangeInputs.style.display = 'none'; quarterInputs.style.display = 'flex';
        }
    }
    filterRadios.forEach(radio => radio.addEventListener('change', toggleFilterInputs));
    toggleFilterInputs();

    // ==========================================
    // FUNGSI UTAMA 1: FILTERING DATA LOG 
    // ==========================================
    function updateLogFilter() {
        if (!logTableBody) return;

        const activeAreaRow = document.querySelector('#areaPerformanceBody tr.row-selected');
        const activeSurveyorRow = document.querySelector('#surveyorPerformanceBody tr.row-selected');
        const activeCompositionRow = document.querySelector('#compositionVisitBody tr.row-selected');
        
        const activeAreaId = activeAreaRow?.dataset.areaId;
        const activeUsername = activeSurveyorRow?.dataset.username;
        const activeKategori = activeCompositionRow?.dataset.kategori;
        
        const vipFilter = vipFilterDropdown ? vipFilterDropdown.value : 'all';
        const searchText = searchInput ? searchInput.value.toLowerCase() : '';

        if (activeAreaId || activeUsername || activeKategori || vipFilter !== 'all' || searchText !== '') {
            if(resetLogBtn) resetLogBtn.style.display = 'inline-block';
        } else {
            if(resetLogBtn) resetLogBtn.style.display = 'none';
        }

        // Filter data dan simpan ke variabel currentFilteredData
        currentFilteredData = rawData.filter(visit => {
            if (activeAreaId && String(visit.master_area_id) !== String(activeAreaId)) return false;
            if (activeUsername && visit.username !== activeUsername) return false;
            if (vipFilter !== 'all' && visit.vip_farm !== vipFilter) return false;
            
            if (activeKategori) {
                const nonOtherVisits = ['Layer', 'Agen', 'Sub Agen', 'Kantor', 'Koordinasi', 'Grower', 'New Customers', 'Seminar', 'CRM Broiler', 'CRM DOC', 'CRM Layer'];
                let matchKategori = false;
                if (activeKategori === 'Layer') matchKategori = (visit.kategori_visit === 'Layer');
                else if (activeKategori === 'Agen/Subagen/Lainnya') matchKategori = ['Agen', 'Sub Agen', 'Kantor'].includes(visit.kategori_visit);
                else if (activeKategori === 'Others') matchKategori = !nonOtherVisits.includes(visit.kategori_visit);
                else {
                    const directMap = { 'Demoplot DOC': 'Grower' };
                    const expectedVisitType = directMap[activeKategori] || activeKategori;
                    matchKategori = (visit.kategori_visit === expectedVisitType);
                }
                if (!matchKategori) return false;
            }

            if (searchText) {
                const rowString = Object.values(visit).join(' ').toLowerCase();
                if (!rowString.includes(searchText)) return false;
            }
            return true;
        });

        currentPage = 1; // Reset ke halaman 1 setiap kali filter berubah
        renderTableData();
    }

    // ==========================================
    // FUNGSI UTAMA 2: RENDER HTML LOG (BERDASARKAN HALAMAN)
    // ==========================================
    function renderTableData() {
        if (!logTableBody) return;

        if (currentFilteredData.length === 0) {
            logTableBody.innerHTML = `
                <tr><td colspan="13" class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                    Tidak ada data log yang cocok.
                </td></tr>`;
            renderPaginationUI(0, 0); // Hapus paginasi
            return;
        }

        // Kalkulasi data yang harus tampil di halaman saat ini
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const dataToRender = currentFilteredData.slice(startIndex, endIndex);
        
        let htmlContent = '';
        dataToRender.forEach(visit => {
            htmlContent += `
                <tr class="visit-row" 
                    data-username="${escapeHTML(visit.username)}" 
                    data-jenis-visit="${escapeHTML(visit.kategori_visit)}"
                    data-area-id="${escapeHTML(visit.master_area_id)}"
                    data-vip="${escapeHTML(visit.vip_farm)}" style="transition: all 0.3s ease;">
                    <td class="text-center visit-cell-icon"><i class="fas fa-chevron-right expand-icon"></i></td>
                    <td class="visit-cell"><span class="badge badge-primary" style="border-radius: 20px; padding: 6px 12px;">${escapeHTML(visit.username)}</span></td>
                    <td class="visit-cell">${escapeHTML(visit.kategori_visit)}</td>
                    <td class="visit-cell wide"><strong>${escapeHTML(visit.nama_customer)}</strong></td>
                    <td class="visit-cell text-center">${escapeHTML(visit.vip_farm)}</td>
                    <td class="visit-cell">${escapeHTML(visit.kapasitas)}</td>
                    <td class="visit-cell"><small class="text-muted"><i class="far fa-clock mr-1"></i>${formatTanggal(visit.waktu_kunjungan)}</small></td>
                    <td class="visit-cell wide">${escapeHTML(visit.tujuan_kunjungan)}</td>
                    <td class="visit-cell">${escapeHTML(visit.jenis_kasus)}</td>
                    <td class="visit-cell">${escapeHTML(visit.pakan)}</td>
                    <td class="visit-cell extra-wide"><small>${escapeHTML(visit.location_address)}</small></td>
                    <td class="text-center visit-cell"><a href="https://www.google.com/maps/search/?api=1&query=${escapeHTML(visit.latitude)},${escapeHTML(visit.longitude)}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill" onclick="event.stopPropagation();"><i class="fas fa-map-marker-alt mr-1"></i>Lihat</a></td>
                    <td class="visit-cell extra-wide"><small>${escapeHTML(visit.catatan)}</small></td>
                </tr>`;
        });

        logTableBody.innerHTML = htmlContent;
        
        // Buat tombol UI Paginasi di bawah tabel
        const totalPages = Math.ceil(currentFilteredData.length / rowsPerPage);
        renderPaginationUI(totalPages, currentFilteredData.length, startIndex + 1, Math.min(endIndex, currentFilteredData.length));
    }

    // ==========================================
    // FUNGSI UTAMA 3: MEMBUAT UI PAGINASI
    // ==========================================
    function renderPaginationUI(totalPages, totalData, startNum, endNum) {
        let paginationContainer = document.getElementById('logPaginationControls');
        
        // Buat container jika belum ada
        if (!paginationContainer) {
            paginationContainer = document.createElement('div');
            paginationContainer.id = 'logPaginationControls';
            paginationContainer.className = 'd-flex justify-content-between align-items-center p-3 bg-light border-top';
            // Sisipkan tepat di bawah tabel-responsive
            document.querySelector('#dataTableVisitDetails').parentElement.after(paginationContainer);
        }

        if (totalData === 0) {
            paginationContainer.innerHTML = '';
            return;
        }

        let buttonsHTML = `<div class="btn-group shadow-sm">`;
        
        // Tombol Prev
        buttonsHTML += `<button class="btn btn-sm btn-white border ${currentPage === 1 ? 'disabled' : ''}" onclick="goToPage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></button>`;
        
        // Logika sederhana untuk menampilkan maksimal 5 tombol halaman
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

        if (startPage > 1) {
            buttonsHTML += `<button class="btn btn-sm btn-white border" onclick="goToPage(1)">1</button>`;
            if (startPage > 2) buttonsHTML += `<button class="btn btn-sm btn-white border disabled">...</button>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === currentPage ? 'btn-primary text-white' : 'btn-white border';
            buttonsHTML += `<button class="btn btn-sm ${activeClass}" onclick="goToPage(${i})">${i}</button>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) buttonsHTML += `<button class="btn btn-sm btn-white border disabled">...</button>`;
            buttonsHTML += `<button class="btn btn-sm btn-white border" onclick="goToPage(${totalPages})">${totalPages}</button>`;
        }

        // Tombol Next
        buttonsHTML += `<button class="btn btn-sm btn-white border ${currentPage === totalPages ? 'disabled' : ''}" onclick="goToPage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></button>`;
        buttonsHTML += `</div>`;

        // Render tulisan info + tombol
        paginationContainer.innerHTML = `
            <small class="text-muted font-weight-bold">Menampilkan ${startNum} - ${endNum} dari total ${new Intl.NumberFormat('id-ID').format(totalData)} data</small>
            ${totalPages > 1 ? buttonsHTML : ''}
        `;
    }

    // Fungsi yang dipanggil saat tombol halaman diklik
    window.goToPage = function(pageNumber) {
        const totalPages = Math.ceil(currentFilteredData.length / rowsPerPage);
        if (pageNumber < 1 || pageNumber > totalPages) return;
        currentPage = pageNumber;
        renderTableData();
        // Auto scroll kembali ke atas tabel agar rapi
        document.querySelector('#dataTableVisitDetails').parentElement.scrollTop = 0;
    };

    // --- EVENT LISTENERS ---
    if (vipFilterDropdown) vipFilterDropdown.addEventListener('change', updateLogFilter);
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => updateLogFilter(), 300);
        });
    }

    if (logTableBody) {
        logTableBody.addEventListener('click', function(e) {
            if (e.target.closest('.btn-outline-primary')) return;
            const row = e.target.closest('.visit-row');
            if (!row) return;

            const allRows = logTableBody.querySelectorAll('.visit-row');
            if (row.classList.contains('expanded')) {
                row.classList.remove('expanded');
            } else {
                allRows.forEach(r => r.classList.remove('expanded'));
                row.classList.add('expanded');
            }
        });
    }

    // --- LOGIC AJAX AREA & SURVEYOR (Tetap sama, memanggil updateLogFilter di akhir) ---
    if (areaTableBody) {
        areaTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.area-row');
            if (!row) return;
            const areaId = row.dataset.areaId;
            const areaName = row.dataset.areaName;

            areaTableBody.querySelectorAll('.area-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');

            if(surveyorTableBody) surveyorTableBody.innerHTML = `<tr><td colspan="8" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></td></tr>`;
            if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></td></tr>`;

            updateLogFilter(); 

            const formData = new URLSearchParams();
            formData.append('area_id', areaId);
            formData.append('start_date', currentStartDate);
            formData.append('end_date', currentEndDate);
            formData.append(csrfTokenName, csrfTokenHash);

            fetch(`${siteUrl}/get_surveyors_for_area_ajax`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => { csrfTokenHash = res.headers.get('X-CSRF-TOKEN') || csrfTokenHash; return res.json(); })
            .then(data => {
                if (data.status === 'success') {
                    updateSurveyorTable(data.surveyor_data, areaName);
                    updateCompositionTable(data.composition_data); 
                    if(resetSurveyorBtn) resetSurveyorBtn.style.display = 'inline-block';
                    if(resetCompBtn) resetCompBtn.style.display = 'none';
                }
            });
        });
    }

    if (surveyorTableBody) {
        surveyorTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.surveyor-row');
            if (!row) return;
            const userId = row.dataset.userId;
            
            surveyorTableBody.querySelectorAll('.surveyor-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');

            if(compositionTableBody) compositionTableBody.innerHTML = `<tr><td colspan="2" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></td></tr>`;
            updateLogFilter(); 
            
            const formData = new URLSearchParams();
            formData.append('user_id', userId);
            formData.append('start_date', currentStartDate);
            formData.append('end_date', currentEndDate);
            formData.append(csrfTokenName, csrfTokenHash);

            fetch(`${siteUrl}/get_data_for_surveyor_ajax`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => { csrfTokenHash = res.headers.get('X-CSRF-TOKEN') || csrfTokenHash; return res.json(); })
            .then(data => {
                if (data.status === 'success') {
                    updateCompositionTable(data.composition_data);
                    if(resetCompBtn) resetCompBtn.style.display = 'inline-block';
                }
            });
        });
    }

    if (compositionTableBody) {
        compositionTableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.composition-row');
            if (!row || !row.dataset.kategori) return;
            compositionTableBody.querySelectorAll('.composition-row').forEach(r => r.classList.remove('row-selected'));
            row.classList.add('row-selected');
            updateLogFilter(); 
            if(resetCompBtn) resetCompBtn.style.display = 'inline-block';
        });
    }

    // --- RESET BUTTONS ---
    const resetAllFilters = (e) => { e.preventDefault(); location.reload(); };
    if(resetAreaBtn) resetAreaBtn.addEventListener('click', resetAllFilters);
    if(resetSurveyorBtn) resetSurveyorBtn.addEventListener('click', resetAllFilters);
    if(resetCompBtn) resetCompBtn.addEventListener('click', resetAllFilters);
    
    if(resetLogBtn) {
        resetLogBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if(vipFilterDropdown) vipFilterDropdown.value = 'all';
            if(searchInput) searchInput.value = '';
            document.querySelectorAll('.row-selected').forEach(row => row.classList.remove('row-selected'));
            if(resetSurveyorBtn) resetSurveyorBtn.style.display = 'none';
            if(resetCompBtn) resetCompBtn.style.display = 'none';
            updateLogFilter();
        });
    }

    // Helper functions for updating Area/Surveyor tables...
    function updateSurveyorTable(data, areaName) {
        if (!surveyorTableBody) return;
        surveyorTableBody.innerHTML = ''; 
        if (!data || data.length === 0) return;
        let no = 1;
        data.forEach(row => {
            // const percent = parseFloat(row.achievement_percent).toFixed(1);
            // const percentVip = parseFloat(row.achievement_percent_vip).toFixed(1);

            const percent = parseFloat(row.achievement_percent).toFixed(1);
            const percentNum = parseFloat(row.achievement_percent);
            const percentVip = parseFloat(row.achievement_percent_vip).toFixed(1);
            const percentNumVip = parseFloat(row.achievement_percent_vip);

            const colorStandar = (percentNum >= 100) ? '#28a745' : '#ffc107'; 
            const colorVip = (percentNumVip >= 100) ? '#28a745' : '#dc3545';
            
            const tr = `
                <tr class="surveyor-row" data-user-id="${row.id_user}" data-username="${escapeHTML(row.surveyor_name)}" style="cursor: pointer; transition: all 0.3s ease;">
                    <td class="text-center align-middle"><span class="badge badge-light rounded-circle">${no++}</span></td>
                    <td class="align-middle"><div class="d-flex align-items-center"><strong>${escapeHTML(row.surveyor_name)}</strong></div></td>
                    <td class="text-center align-middle"><span class="badge badge-light px-3 py-2" style="font-size: 14px;">${new Intl.NumberFormat('id-ID').format(row.target)}</span></td>
                    <td class="text-center align-middle"><span class="badge badge-info px-3 py-2" style="font-size: 14px;">${new Intl.NumberFormat('id-ID').format(row.aktual)}</span></td>
                    <td class="align-middle text-center" style="font-weight: bold; color: ${colorStandar}; font-size: 16px;">${percent}%</td>
                    <td class="text-center align-middle"><span class="badge badge-danger px-2 py-1" style="font-size: 12px; opacity: 0.8;"><i class="fas fa-star fa-xs mr-1"></i>${new Intl.NumberFormat('id-ID').format(row.target_vip)}</span></td>
                    <td class="text-center align-middle"><span class="badge badge-warning px-2 py-1" style="font-size: 12px; opacity: 0.8;"><i class="fas fa-star fa-xs mr-1"></i>${new Intl.NumberFormat('id-ID').format(row.aktual_vip)}</span></td>
                    <td class="align-middle text-center" style="font-weight: bold; color: ${colorVip}; font-size: 13px; opacity: 0.8;"><i class="fas fa-star fa-xs mr-1"></i>${percentVip}%</td>
                </tr>
            `;
            
            // const tr = `<tr class="surveyor-row" data-user-id="${row.id_user}" data-username="${escapeHTML(row.surveyor_name)}" style="cursor: pointer;">
            //     <td class="text-center"><span class="badge badge-light rounded-circle">${no++}</span></td>
            //     <td><strong>${escapeHTML(row.surveyor_name)}</strong></td>
            //     <td class="text-center">${new Intl.NumberFormat('id-ID').format(row.target)}</td>
            //     <td class="text-center">${new Intl.NumberFormat('id-ID').format(row.aktual)}</td>
            //     <td class="text-center font-weight-bold">${percent}%</td>
            //     <td class="text-center">${new Intl.NumberFormat('id-ID').format(row.target_vip)}</td>
            //     <td class="text-center">${new Intl.NumberFormat('id-ID').format(row.aktual_vip)}</td>
            //     <td class="text-center font-weight-bold">${percentVip}%</td>
            // </tr>`;
            surveyorTableBody.insertAdjacentHTML('beforeend', tr);
        });
    }

    function updateCompositionTable(data) {
        if (!compositionTableBody) return;
        compositionTableBody.innerHTML = ''; 
        if (!data || data.length === 0) return;
        const colors = ['#1e3c72', '#2a5298', '#3498db', '#5dade2', '#21618c', '#1a5490', '#154360'];
        data.forEach((row, index) => {
            const color = colors[index % colors.length];
            const percent = parseFloat(row.persentase).toFixed(2);
            const tr = `<tr class="composition-row" data-kategori="${escapeHTML(row.kategori)}" style="cursor: pointer;">
                <td><div class="d-flex align-items-center"><div style="width: 12px; height: 12px; border-radius: 50%; background-color: ${color}; margin-right: 12px;"></div>${escapeHTML(row.kategori)}</div></td>
                <td class="text-right"><strong style="color: ${color}; font-size: 16px;">${percent}%</strong></td>
            </tr>`;
            compositionTableBody.insertAdjacentHTML('beforeend', tr);
        });
    }

    // TRIGGER AWAL
    updateLogFilter();
    
    // RBAC
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