<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <section class="content-header mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center p-3 bg-white shadow-sm" style="border-radius: 2rem;">
                <a href="<?= site_url('Dashboard_new/index') ?>" class="btn btn-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                    <i class="fas fa-home fa-lg text-white"></i>
                </a>
                <h1 class="font-weight-bold text-dark mb-0 mx-auto" style="font-size: 2rem;">Data Kunjungan</h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="col-sm-12">

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- CARD: FILTER                                        --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filter Laporan</h3>
                </div>
                <div class="card-body p-4">
                    <form action="<?= site_url('Dashboard_new/visual_data_kunjungan') ?>" method="post" class="row g-3 align-items-end">

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-secondary">Pilih Tipe Filter:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_type_range" value="range"
                                    <?= ($filter_type == 'range') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="filter_type_range">Range Bulan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_type_quarter" value="quarter"
                                    <?= ($filter_type == 'quarter') ? 'checked' : '' ?>>
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
                                        style="border-radius: 10px;" value="<?= $selected_start ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Sampai Bulan
                                    </label>
                                    <input type="month" name="end_date" id="end_date" class="form-control"
                                        style="border-radius: 10px;" value="<?= $selected_end ?>">
                                </div>
                            </div>

                            <div class="row g-3" id="quarter_filter_inputs">
                                <div class="col-md-6">
                                    <label for="quarter" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Triwulan
                                    </label>
                                    <select name="quarter" id="quarter" class="form-control form-select" style="border-radius: 10px;">
                                        <?php foreach (['Q1' => 'Q1 (Januari - Maret)', 'Q2' => 'Q2 (April - Juni)', 'Q3' => 'Q3 (Juli - September)', 'Q4' => 'Q4 (Oktober - Desember)'] as $val => $label): ?>
                                            <option value="<?= $val ?>" <?= ($selected_quarter == $val) ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quarter_year" class="form-label fw-bold text-secondary">
                                        <i class="fas fa-calendar mr-2"></i>Tahun
                                    </label>
                                    <select name="quarter_year" id="quarter_year" class="form-control form-select" style="border-radius: 10px;">
                                        <?php for ($i = date('Y'); $i >= date('Y') - 7; $i--): ?>
                                            <option value="<?= $i ?>" <?= ($selected_quarter_year == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor ?>
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

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- CARD: PERFORMA AREA                                 --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0" style="color: #2c3e50;">
                            <i class="fas fa-map-marked-alt mr-2" style="color: #f39c12;"></i>Laporan Performa per Area
                        </h3>
                        <a id="resetAreaFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius: 20px; font-size: 0.8rem;">
                            <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-no-hscroll" style="max-height: 500px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:35%;">Area</th>
                                    <th class="text-center" style="width:25%;">Total Target</th>
                                    <th class="text-center" style="width:25%;">Total Aktual</th>
                                    <th class="text-center" style="width:15%;">Pencapaian</th>
                                </tr>
                            </thead>
                            <tbody id="areaPerformanceBody">
                                <?php if (empty($area_performance_data)): ?>
                                    <tr><td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:.3;"></i>Tidak ada data
                                    </td></tr>
                                <?php else: ?>
                                    <?php foreach ($area_performance_data as $row): ?>
                                        <tr class="area-row"
                                            data-area-id="<?= $row['master_area_id'] ?>"
                                            data-area-name="<?= htmlspecialchars($row['nama_area']) ?>"
                                            style="cursor:pointer;">
                                            <td class="align-middle">
                                                <strong style="color:#2c3e50;">
                                                    <i class="fas fa-map-marker-alt mr-2 d-none d-sm-inline" style="color:#3498db;"></i>
                                                    <?= htmlspecialchars($row['nama_area']) ?>
                                                </strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light px-3 py-2" style="font-size:14px;">
                                                    <?= number_format($row['total_target']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info px-3 py-2" style="font-size:14px;">
                                                    <?= number_format($row['total_aktual']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle pct-cell"
                                                data-pct="<?= round($row['achievement_percent'], 1) ?>">
                                                <?= round($row['achievement_percent'], 1) ?>%
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- CARD: PERFORMA SURVEYOR                             --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0" style="color:#2c3e50;">
                            <i class="fas fa-users mr-2" style="color:#e74c3c;"></i>Laporan Performa Surveyor
                        </h3>
                        <a id="resetSurveyorFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius:20px; font-size:0.8rem;">
                            <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua Area
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:500px;">
                        <table class="table table-hover mb-0" style="font-size:11pt;">
                            <thead class="thead-sticky">
                                <tr>
                                    <th class="text-center" style="width:60px;">No.</th>
                                    <th>Surveyor</th>
                                    <th class="text-center">Target Total</th>
                                    <th class="text-center">Aktual Total</th>
                                    <th class="text-center">Pencapaian Total</th>
                                    <th class="text-center">Target VIP</th>
                                    <th class="text-center">Aktual VIP</th>
                                    <th class="text-center">Pencapaian VIP</th>
                                </tr>
                            </thead>
                            <tbody id="surveyorPerformanceBody">
                                <?php if (empty($performance_data)): ?>
                                    <tr><td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:.3;"></i>Tidak ada data
                                    </td></tr>
                                <?php else: ?>
                                    <?php $no = 1; foreach ($performance_data as $row): ?>
                                        <tr class="surveyor-row"
                                            data-user-id="<?= $row['id_user'] ?>"
                                            data-username="<?= htmlspecialchars($row['surveyor_name']) ?>"
                                            style="cursor:pointer;">
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light rounded-pill"><?= $no++ ?></span>
                                            </td>
                                            <td class="align-middle"><strong><?= htmlspecialchars($row['surveyor_name']) ?></strong></td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-light px-3 py-2" style="font-size:14px;"><?= number_format($row['target']) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info px-3 py-2" style="font-size:14px;"><?= number_format($row['aktual']) ?></span>
                                            </td>
                                            <td class="text-center align-middle pct-cell"
                                                data-pct="<?= round($row['achievement_percent'], 1) ?>">
                                                <?= round($row['achievement_percent'], 1) ?>%
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-danger px-2 py-1" style="font-size:12px;opacity:.8;">
                                                    <i class="fas fa-star fa-xs mr-1"></i><?= number_format($row['target_vip']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-warning px-2 py-1" style="font-size:12px;opacity:.8;">
                                                    <i class="fas fa-star fa-xs mr-1"></i><?= number_format($row['aktual_vip']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle pct-cell pct-vip"
                                                data-pct="<?= round($row['achievement_percent_vip'], 1) ?>"
                                                style="font-size:13px;opacity:.8;">
                                                <i class="fas fa-star fa-xs mr-1"></i><?= round($row['achievement_percent_vip'], 1) ?>%
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- CARD: KOMPOSISI VISIT                               --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0" style="color:#2c3e50;">
                            <i class="fas fa-chart-pie mr-2" style="color:#9b59b6;"></i>Komposisi Visit
                        </h3>
                        <a id="resetCompositionFilter" class="btn btn-sm btn-outline-secondary" style="display:none; border-radius:20px; font-size:0.8rem;">
                            <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive table-no-hscroll" style="max-height:500px;">
                        <table class="table table-hover mb-0" style="font-size:11pt;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Komoditas / Tujuan</th>
                                    <th class="text-right" style="width:100px;">Persentase</th>
                                </tr>
                            </thead>
                            <tbody id="compositionVisitBody">
                                <?php if (empty($visit_breakdown_data)): ?>
                                    <tr><td colspan="2" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:.3;"></i>Tidak ada data
                                    </td></tr>
                                <?php else: ?>
                                    <?php
                                    $comp_colors = ['#1e3c72','#2a5298','#3498db','#5dade2','#21618c','#1a5490','#154360'];
                                    foreach ($visit_breakdown_data as $idx => $row):
                                        $color = $comp_colors[$idx % count($comp_colors)];
                                    ?>
                                        <tr class="composition-row" data-kategori="<?= htmlspecialchars($row['kategori']) ?>" style="cursor:pointer;">
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div style="width:12px;height:12px;border-radius:50%;background:<?= $color ?>;margin-right:12px;flex-shrink:0;"></div>
                                                    <?= htmlspecialchars($row['kategori']) ?>
                                                </div>
                                            </td>
                                            <td class="text-right align-middle">
                                                <strong style="color:<?= $color ?>;font-size:16px;"><?= round($row['persentase'], 2) ?>%</strong>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- CARD: DETAIL LOG KUNJUNGAN                          --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h4 class="card-title font-weight-bold mb-0" style="color:#2c3e50;">
                                <i class="fas fa-clipboard-list mr-2" style="color:#16a085;"></i>Detail Log Kunjungan
                            </h4>
                            <a id="resetLogFilter" class="btn btn-sm btn-outline-secondary ml-2" style="display:none; border-radius:20px; font-size:0.8rem; vertical-align:middle;">
                                <i class="fas fa-sync-alt mr-1"></i> Tampilkan Semua
                            </a>
                            <small class="text-muted d-block mt-1">Klik baris untuk detail lengkap</small>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0">
                            <label for="vipFilter" class="form-label text-secondary mb-1" style="font-size:0.85rem;">
                                <i class="fas fa-star mr-1" style="color:#ffc107;"></i>Filter VIP:
                            </label>
                            <select id="vipFilter" class="form-control form-select" style="border-radius:10px; font-size:0.9rem;">
                                <option value="all">Semua</option>
                                <option value="Ya">VIP Saja</option>
                                <option value="Tidak">Non-VIP Saja</option>
                                <option value="-">Tidak Ada Info VIP</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius:10px 0 0 10px;">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-left-0" id="searchInput"
                                    placeholder="Cari data pelanggan, area..."
                                    style="border-radius:0 10px 10px 0; border-left:none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:700px; overflow-y:auto;">
                        <table class="table mb-0" id="dataTableVisitDetails">
                            <thead class="thead-sticky">
                                <tr>
                                    <th style="width:30px;"></th>
                                    <th style="width:120px;">Username</th>
                                    <th style="width:130px;">Jenis Visit</th>
                                    <th style="width:100px;">Nama Customer</th>
                                    <th class="text-center" style="width:80px;">VIP</th>
                                    <th style="width:100px;">Kapasitas</th>
                                    <th style="width:100px;">Waktu</th>
                                    <th style="width:100px;">Tujuan</th>
                                    <th style="width:120px;">Kasus</th>
                                    <th style="width:120px;">Pakan</th>
                                    <th style="width:100px;">Alamat</th>
                                    <th class="text-center" style="width:100px;">Lokasi</th>
                                    <th style="width:150px;">Catatan</th>
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

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- STYLES                                                          --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<style>
/* ── Scrollbar ──────────────────────────────────────────── */
.table-responsive::-webkit-scrollbar { width: 8px; height: 8px; }
.table-responsive::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.table-responsive::-webkit-scrollbar-thumb { background: #2a5298; border-radius: 10px; }
.table-responsive::-webkit-scrollbar-thumb:hover { background: #1e3c72; }

.table-no-hscroll { overflow-x: hidden !important; overflow-y: auto !important; }
.table-no-hscroll::-webkit-scrollbar { width: 8px; height: 0; }
.table-no-hscroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.table-no-hscroll::-webkit-scrollbar-thumb { background: #2a5298; border-radius: 10px; }

/* ── Thead sticky ───────────────────────────────────────── */
.thead-sticky { background-color: #f8f9fa; position: sticky; top: 0; z-index: 10; }
.thead-sticky th,
.thead-light th { border-top: none; }

/* ── Row hover & selection ──────────────────────────────── */
.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}

/* Satu class untuk semua tabel — tidak perlu duplikasi per-id */
tr.row-selected,
tr.row-selected td {
    background-color: #d6d8db !important;
    border-left: 4px solid #6c757d !important;
}
tr.row-selected {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(108,117,125,.3);
}
tr.row-selected:hover td { background-color: #ced4da !important; }

/* ── Compact rows ───────────────────────────────────────── */
#areaPerformanceBody td,
#surveyorPerformanceBody td { padding: 5px 8px; vertical-align: middle; }

#surveyorPerformanceBody .badge.rounded-pill,
#surveyorPerformanceBody .badge.rounded-circle {
    padding: 0.3em 0.65em;
    font-size: 13px;
    border-radius: 50rem !important;
}

/* ── Achievement color classes (menggantikan inline style PHP) ── */
.pct-ok   { color: #28a745 !important; font-weight: 600; }
.pct-warn { color: #ffc107 !important; font-weight: 600; }
.pct-bad  { color: #dc3545 !important; font-weight: 600; }

/* ── Buttons & controls ─────────────────────────────────── */
.btn-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    transition: transform .2s, box-shadow .2s;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(42,82,152,.4); }

.form-control:focus,
.form-select:focus { border-color: #2a5298; box-shadow: 0 0 0 .2rem rgba(42,82,152,.15); }

#vipFilter:focus { border-color: #ffc107; box-shadow: 0 0 0 .2rem rgba(255,193,7,.15); }

/* ── Card hover ─────────────────────────────────────────── */
.card { transition: transform .2s, box-shadow .2s; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.1) !important; }

/* ── Visit log rows ─────────────────────────────────────── */
.visit-row { cursor: pointer; border-bottom: 1px solid #e0e0e0; transition: background .2s; }
.visit-row.expanded { background-color: #e3f2fd; box-shadow: 0 2px 8px rgba(0,0,0,.08); }

.visit-cell {
    padding: 12px;
    vertical-align: top;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.visit-cell.wide,
.visit-cell.extra-wide { max-width: 100px; }
.visit-cell-icon { padding: 12px; vertical-align: middle; width: 30px; }

.visit-row.expanded .visit-cell {
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    padding: 16px 12px;
}

.expand-icon { transition: transform .3s; color: #2a5298; font-size: 12px; }
.visit-row.expanded .expand-icon { transform: rotate(90deg); }

#dataTableVisitDetails { table-layout: fixed; width: 100%; }

/* ── Mobile (≤768px) ────────────────────────────────────── */
@media (max-width: 768px) {
    .card-header .row { flex-direction: column; gap: .5rem; }
    .form-check.form-check-inline { display: block !important; margin-bottom: .5rem; margin-right: 0 !important; }
    .col-md-10, .col-md-2 { width: 100% !important; padding-left: 0; padding-right: 0; }
    .col-md-10 .col-md-6 { width: 100% !important; margin-bottom: .75rem; padding: 0; }
    .col-md-2 .btn { width: 100% !important; }

    .card-body .table-responsive { max-height: 400px !important; }

    #dataTableVisitDetails { table-layout: auto !important; }
    #dataTableVisitDetails th,
    #dataTableVisitDetails td { font-size: .6rem !important; padding: 3px 2px !important; }
    #dataTableVisitDetails thead th { font-size: .55rem !important; padding: 4px 2px !important; }

    #dataTableVisitDetails thead th:nth-child(1),
    #dataTableVisitDetails tbody td:nth-child(1) { width: 25px !important; }
    #dataTableVisitDetails thead th:nth-child(2),
    #dataTableVisitDetails tbody td:nth-child(2) { width: 60px !important; }
    #dataTableVisitDetails thead th:nth-child(3),
    #dataTableVisitDetails tbody td:nth-child(3) { width: 70px !important; }
    #dataTableVisitDetails thead th:nth-child(4),
    #dataTableVisitDetails tbody td:nth-child(4) { width: 80px !important; }
    #dataTableVisitDetails thead th:nth-child(5),
    #dataTableVisitDetails tbody td:nth-child(5) { width: 35px !important; }
    #dataTableVisitDetails thead th:nth-child(6),
    #dataTableVisitDetails tbody td:nth-child(6) { width: 50px !important; }
    #dataTableVisitDetails thead th:nth-child(7),
    #dataTableVisitDetails tbody td:nth-child(7) { width: 75px !important; }
    #dataTableVisitDetails thead th:nth-child(8),
    #dataTableVisitDetails tbody td:nth-child(8) { width: 70px !important; }
    #dataTableVisitDetails thead th:nth-child(9),
    #dataTableVisitDetails tbody td:nth-child(9) { width: 60px !important; }
    #dataTableVisitDetails thead th:nth-child(10),
    #dataTableVisitDetails tbody td:nth-child(10) { width: 60px !important; }
    #dataTableVisitDetails thead th:nth-child(11),
    #dataTableVisitDetails tbody td:nth-child(11) { width: 80px !important; }
    #dataTableVisitDetails thead th:nth-child(13),
    #dataTableVisitDetails tbody td:nth-child(13) { width: 100px !important; }

    .visit-cell, .visit-row.expanded .visit-cell { padding: 3px 2px !important; font-size: .6rem !important; }
    .visit-cell.wide, .visit-cell.extra-wide { max-width: 80px !important; }
    .expand-icon { font-size: 8px !important; }
    .visit-cell-icon { padding: 3px !important; width: 20px !important; }
}

@media (max-width: 480px) {
    #dataTableVisitDetails th,
    #dataTableVisitDetails td { font-size: .55rem !important; padding: 2px 1px !important; }
    #dataTableVisitDetails thead th { font-size: .5rem !important; padding: 3px 1px !important; }
    #dataTableVisitDetails thead th:nth-child(2), #dataTableVisitDetails tbody td:nth-child(2) { width: 50px !important; }
    #dataTableVisitDetails thead th:nth-child(3), #dataTableVisitDetails tbody td:nth-child(3) { width: 60px !important; }
    #dataTableVisitDetails thead th:nth-child(4), #dataTableVisitDetails tbody td:nth-child(4) { width: 70px !important; }
    #dataTableVisitDetails thead th:nth-child(7), #dataTableVisitDetails tbody td:nth-child(7) { width: 65px !important; }
    #dataTableVisitDetails thead th:nth-child(11), #dataTableVisitDetails tbody td:nth-child(11) { width: 70px !important; }
    #dataTableVisitDetails thead th:nth-child(13), #dataTableVisitDetails tbody td:nth-child(13) { width: 70px !important; }
}
</style>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- JAVASCRIPT                                                       --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── CONFIG ─────────────────────────────────────────── */
    var CFG = {
        siteUrl:    '<?= site_url('Dashboard_new') ?>',
        startDate:  '<?= $js_start_date ?? '' ?>',
        endDate:    '<?= $js_end_date ?? '' ?>',
        csrfName:   '<?= $this->security->get_csrf_token_name() ?>',
        csrfHash:   '<?= $this->security->get_csrf_hash() ?>',
        rawData:    <?= json_encode($visit_details_table ?? []) ?>,
        LIMIT:      200,
        COLORS:     ['#1e3c72','#2a5298','#3498db','#5dade2','#21618c','#1a5490','#154360'],
        MONTHS:     ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
        NON_OTHER:  new Set(['Layer','Agen','Sub Agen','Kantor','Koordinasi','Grower',
                             'New Customers','Seminar','CRM Broiler','CRM DOC','CRM Layer']),
        KAT_MAP:    { 'Demoplot DOC': 'Grower' }
    };

    /* ── DOM REFS ───────────────────────────────────────── */
    var gel = function(id) { return document.getElementById(id); };
    var elArea     = gel('areaPerformanceBody');
    var elSurveyor = gel('surveyorPerformanceBody');
    var elComp     = gel('compositionVisitBody');
    var elLog      = gel('visitTableBody');
    var elVip      = gel('vipFilter');
    var elSearch   = gel('searchInput');
    var resetBtns  = {
        area:     gel('resetAreaFilter'),
        surveyor: gel('resetSurveyorFilter'),
        comp:     gel('resetCompositionFilter'),
        log:      gel('resetLogFilter')
    };

    /* ── UTILS ──────────────────────────────────────────── */
    var _tmpDiv = document.createElement('div');
    function esc(str) {
        if (str == null) return '';
        _tmpDiv.textContent = String(str);
        return _tmpDiv.innerHTML;
    }

    function fmtDate(str) {
        if (!str) return '-';
        var d = new Date(str);
        if (isNaN(d)) return str;
        var pad = function(n) { return String(n).padStart(2, '0'); };
        return pad(d.getDate()) + ' ' + CFG.MONTHS[d.getMonth()] + ' ' + d.getFullYear()
             + ', ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    }

    function fmtNum(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }

    function pctClass(pct, isVip) {
        if (pct >= 100) return 'pct-ok';
        return isVip ? 'pct-bad' : 'pct-warn';
    }

    function spinnerRow(cols, msg) {
        return '<tr><td colspan="' + cols + '" class="text-center py-5">'
             + '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>'
             + '<p class="mt-2 text-muted">' + esc(msg) + '</p></td></tr>';
    }

    function emptyRow(cols, msg) {
        msg = msg || 'Tidak ada data';
        return '<tr><td colspan="' + cols + '" class="text-center py-5 text-muted">'
             + '<i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:.3;"></i>' + esc(msg)
             + '</td></tr>';
    }

    /* Terapkan kelas warna achievement ke semua .pct-cell yang ada di DOM */
    function applyPctColors(scope) {
        var cells = (scope || document).querySelectorAll('.pct-cell');
        cells.forEach(function(td) {
            var pct  = parseFloat(td.dataset.pct || 0);
            var isVip = td.classList.contains('pct-vip');
            td.classList.remove('pct-ok', 'pct-warn', 'pct-bad');
            td.classList.add(pctClass(pct, isVip));
        });
    }

    /* ── FILTER TOGGLE (range vs quarter) ──────────────── */
    var rangeEl   = gel('range_filter_inputs');
    var quarterEl = gel('quarter_filter_inputs');

    function toggleFilter() {
        var val = document.querySelector('input[name="filter_type"]:checked').value;
        rangeEl.style.display   = (val === 'range') ? 'flex' : 'none';
        quarterEl.style.display = (val === 'range') ? 'none' : 'flex';
    }
    document.querySelectorAll('input[name="filter_type"]').forEach(function(r) {
        r.addEventListener('change', toggleFilter);
    });
    toggleFilter();

    /* ── RESET BUTTONS ──────────────────────────────────── */
    ['area', 'surveyor', 'comp'].forEach(function(key) {
        if (resetBtns[key]) {
            resetBtns[key].addEventListener('click', function(e) { e.preventDefault(); location.reload(); });
        }
    });

    if (resetBtns.log) {
        resetBtns.log.addEventListener('click', function(e) {
            e.preventDefault();
            if (elVip)    elVip.value    = 'all';
            if (elSearch) elSearch.value = '';
            document.querySelectorAll('.row-selected').forEach(function(r) { r.classList.remove('row-selected'); });
            Object.keys(resetBtns).forEach(function(k) { if (resetBtns[k]) resetBtns[k].style.display = 'none'; });
            updateLog();
        });
    }

    /* ── AJAX HELPER ────────────────────────────────────── */
    function postAjax(endpoint, body, callback) {
        body[CFG.csrfName] = CFG.csrfHash;
        var params = Object.keys(body).map(function(k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(body[k]);
        }).join('&');

        fetch(CFG.siteUrl + '/' + endpoint, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body:    params
        })
        .then(function(res) {
            CFG.csrfHash = res.headers.get('X-CSRF-TOKEN') || CFG.csrfHash;
            return res.json();
        })
        .then(callback)
        .catch(function(err) { console.error('[postAjax] ' + endpoint, err); });
    }

    /* ── LOG TABLE ──────────────────────────────────────── */
    function getActiveFilters() {
        var areaRow     = document.querySelector('#areaPerformanceBody .row-selected');
        var surveyorRow = document.querySelector('#surveyorPerformanceBody .row-selected');
        var compRow     = document.querySelector('#compositionVisitBody .row-selected');
        return {
            areaId:   areaRow     ? areaRow.dataset.areaId   : null,
            username: surveyorRow ? surveyorRow.dataset.username : null,
            kategori: compRow     ? compRow.dataset.kategori : null,
            vip:      elVip    ? elVip.value          : 'all',
            search:   elSearch ? elSearch.value.toLowerCase() : ''
        };
    }

    function matchKategori(visitType, ak) {
        if (!ak) return true;
        if (ak === 'Layer')              return visitType === 'Layer';
        if (ak === 'Agen/Subagen/Lainnya') return ['Agen','Sub Agen','Kantor'].indexOf(visitType) !== -1;
        if (ak === 'Others')             return !CFG.NON_OTHER.has(visitType);
        return visitType === (CFG.KAT_MAP[ak] || ak);
    }

    function updateLog() {
        if (!elLog) return;
        var f = getActiveFilters();
        var hasFilter = f.areaId || f.username || f.kategori || f.vip !== 'all' || f.search;
        if (resetBtns.log) resetBtns.log.style.display = hasFilter ? 'inline-block' : 'none';

        var filtered = CFG.rawData.filter(function(v) {
            if (f.areaId   && String(v.master_area_id) !== f.areaId)   return false;
            if (f.username && v.username !== f.username)                return false;
            if (f.vip !== 'all' && v.vip_farm !== f.vip)               return false;
            if (!matchKategori(v.kategori_visit, f.kategori))           return false;
            if (f.search && Object.values(v).join(' ').toLowerCase().indexOf(f.search) === -1) return false;
            return true;
        });

        renderLog(filtered);
    }

    function renderLog(data) {
        if (!elLog) return;
        if (!data.length) { elLog.innerHTML = emptyRow(13, 'Tidak ada data log yang cocok.'); return; }

        var slice = data.slice(0, CFG.LIMIT);
        var html  = slice.map(function(v) {
            return '<tr class="visit-row"'
                 + ' data-username="'   + esc(v.username)       + '"'
                 + ' data-jenis-visit="'+ esc(v.kategori_visit) + '"'
                 + ' data-area-id="'    + esc(v.master_area_id) + '"'
                 + ' data-vip="'        + esc(v.vip_farm)       + '">'
                 + '<td class="text-center visit-cell-icon"><i class="fas fa-chevron-right expand-icon"></i></td>'
                 + '<td class="visit-cell"><span class="badge badge-primary" style="border-radius:20px;padding:6px 12px;">' + esc(v.username) + '</span></td>'
                 + '<td class="visit-cell">' + esc(v.kategori_visit) + '</td>'
                 + '<td class="visit-cell wide"><strong>' + esc(v.nama_customer) + '</strong></td>'
                 + '<td class="visit-cell text-center">' + esc(v.vip_farm) + '</td>'
                 + '<td class="visit-cell">' + esc(v.kapasitas) + '</td>'
                 + '<td class="visit-cell"><small class="text-muted"><i class="far fa-clock mr-1"></i>' + fmtDate(v.waktu_kunjungan) + '</small></td>'
                 + '<td class="visit-cell wide">' + esc(v.tujuan_kunjungan) + '</td>'
                 + '<td class="visit-cell">' + esc(v.jenis_kasus) + '</td>'
                 + '<td class="visit-cell">' + esc(v.pakan) + '</td>'
                 + '<td class="visit-cell extra-wide"><small>' + esc(v.location_address) + '</small></td>'
                 + '<td class="text-center visit-cell">'
                 +   '<a href="https://www.google.com/maps/search/?api=1&query=' + esc(v.latitude) + ',' + esc(v.longitude) + '"'
                 +   ' target="_blank" class="btn btn-sm btn-outline-primary rounded-pill" onclick="event.stopPropagation();">'
                 +   '<i class="fas fa-map-marker-alt mr-1"></i>Lihat</a></td>'
                 + '<td class="visit-cell extra-wide"><small>' + esc(v.catatan) + '</small></td>'
                 + '</tr>';
        }).join('');

        if (data.length > CFG.LIMIT) {
            html += '<tr><td colspan="13" class="text-center py-3 bg-light text-muted" style="font-weight:500;">'
                  + '<i class="fas fa-info-circle mr-1"></i>Menampilkan ' + CFG.LIMIT + ' dari ' + data.length + ' data. Gunakan pencarian untuk hasil lebih spesifik.'
                  + '</td></tr>';
        }

        elLog.innerHTML = html;
    }

    /* ── LOG: expandable row ────────────────────────────── */
    if (elLog) {
        elLog.addEventListener('click', function(e) {
            if (e.target.closest('.btn-outline-primary')) return;
            var row = e.target.closest('.visit-row');
            if (!row) return;
            var wasExpanded = row.classList.contains('expanded');
            elLog.querySelectorAll('.visit-row.expanded').forEach(function(r) { r.classList.remove('expanded'); });
            if (!wasExpanded) row.classList.add('expanded');
        });
    }

    /* ── SURVEYOR RENDERER ──────────────────────────────── */
    function renderSurveyor(data, areaName) {
        if (!elSurveyor) return;
        if (!data || !data.length) { elSurveyor.innerHTML = emptyRow(8, 'Tidak ada surveyor untuk ' + (areaName || '') + '.'); return; }

        elSurveyor.innerHTML = data.map(function(row, i) {
            var pct    = parseFloat(row.achievement_percent);
            var pctVip = parseFloat(row.achievement_percent_vip);
            return '<tr class="surveyor-row" data-user-id="' + esc(row.id_user) + '" data-username="' + esc(row.surveyor_name) + '" style="cursor:pointer;">'
                 + '<td class="text-center align-middle"><span class="badge badge-light rounded-pill">' + (i + 1) + '</span></td>'
                 + '<td class="align-middle"><strong>' + esc(row.surveyor_name) + '</strong></td>'
                 + '<td class="text-center align-middle"><span class="badge badge-light px-3 py-2" style="font-size:14px;">' + fmtNum(row.target) + '</span></td>'
                 + '<td class="text-center align-middle"><span class="badge badge-info px-3 py-2" style="font-size:14px;">' + fmtNum(row.aktual) + '</span></td>'
                 + '<td class="text-center align-middle pct-cell ' + pctClass(pct, false) + '" data-pct="' + pct.toFixed(1) + '" style="font-size:16px;">' + pct.toFixed(1) + '%</td>'
                 + '<td class="text-center align-middle"><span class="badge badge-danger px-2 py-1" style="font-size:12px;opacity:.8;"><i class="fas fa-star fa-xs mr-1"></i>' + fmtNum(row.target_vip) + '</span></td>'
                 + '<td class="text-center align-middle"><span class="badge badge-warning px-2 py-1" style="font-size:12px;opacity:.8;"><i class="fas fa-star fa-xs mr-1"></i>' + fmtNum(row.aktual_vip) + '</span></td>'
                 + '<td class="text-center align-middle pct-cell pct-vip ' + pctClass(pctVip, true) + '" data-pct="' + pctVip.toFixed(1) + '" style="font-size:13px;opacity:.8;"><i class="fas fa-star fa-xs mr-1"></i>' + pctVip.toFixed(1) + '%</td>'
                 + '</tr>';
        }).join('');
    }

    /* ── COMPOSITION RENDERER ───────────────────────────── */
    function renderComposition(data) {
        if (!elComp) return;
        if (!data || !data.length) { elComp.innerHTML = emptyRow(2); return; }

        elComp.innerHTML = data.map(function(row, i) {
            var color = CFG.COLORS[i % CFG.COLORS.length];
            var pct   = parseFloat(row.persentase).toFixed(2);
            return '<tr class="composition-row" data-kategori="' + esc(row.kategori) + '" style="cursor:pointer;">'
                 + '<td class="align-middle"><div class="d-flex align-items-center">'
                 + '<div style="width:12px;height:12px;border-radius:50%;background:' + color + ';margin-right:12px;flex-shrink:0;"></div>'
                 + esc(row.kategori) + '</div></td>'
                 + '<td class="text-right align-middle"><strong style="color:' + color + ';font-size:16px;">' + pct + '%</strong></td>'
                 + '</tr>';
        }).join('');
    }

    /* ── AREA CLICK ─────────────────────────────────────── */
    if (elArea) {
        elArea.addEventListener('click', function(e) {
            var row = e.target.closest('.area-row');
            if (!row) return;

            elArea.querySelectorAll('.area-row').forEach(function(r) { r.classList.remove('row-selected'); });
            row.classList.add('row-selected');
            if (resetBtns.area) resetBtns.area.style.display = 'inline-block';

            var areaId   = row.dataset.areaId;
            var areaName = row.dataset.areaName;

            elSurveyor.innerHTML = spinnerRow(8, 'Memuat surveyor untuk ' + areaName + '...');
            elComp.innerHTML     = spinnerRow(2, 'Memuat komposisi...');
            updateLog();

            postAjax('get_surveyors_for_area_ajax', {
                area_id:    areaId,
                start_date: CFG.startDate,
                end_date:   CFG.endDate
            }, function(data) {
                if (data.status === 'success') {
                    renderSurveyor(data.surveyor_data, areaName);
                    renderComposition(data.composition_data);
                    if (resetBtns.surveyor) resetBtns.surveyor.style.display = 'inline-block';
                    if (resetBtns.comp)     resetBtns.comp.style.display     = 'none';
                } else {
                    elSurveyor.innerHTML = emptyRow(8, 'Gagal memuat data surveyor.');
                    elComp.innerHTML     = emptyRow(2, 'Gagal memuat data komposisi.');
                }
            });
        });
    }

    /* ── SURVEYOR CLICK ─────────────────────────────────── */
    if (elSurveyor) {
        elSurveyor.addEventListener('click', function(e) {
            var row = e.target.closest('.surveyor-row');
            if (!row) return;

            elSurveyor.querySelectorAll('.surveyor-row').forEach(function(r) { r.classList.remove('row-selected'); });
            row.classList.add('row-selected');
            elComp.innerHTML = spinnerRow(2, 'Memuat komposisi...');
            updateLog();

            postAjax('get_data_for_surveyor_ajax', {
                user_id:    row.dataset.userId,
                start_date: CFG.startDate,
                end_date:   CFG.endDate
            }, function(data) {
                if (data.status === 'success') {
                    renderComposition(data.composition_data);
                    if (resetBtns.comp) resetBtns.comp.style.display = 'inline-block';
                } else {
                    elComp.innerHTML = emptyRow(2, 'Gagal memuat data.');
                }
            });
        });
    }

    /* ── COMPOSITION CLICK ──────────────────────────────── */
    if (elComp) {
        elComp.addEventListener('click', function(e) {
            var row = e.target.closest('.composition-row');
            if (!row || !row.dataset.kategori) return;
            elComp.querySelectorAll('.composition-row').forEach(function(r) { r.classList.remove('row-selected'); });
            row.classList.add('row-selected');
            if (resetBtns.comp) resetBtns.comp.style.display = 'inline-block';
            updateLog();
        });
    }

    /* ── VIP FILTER & SEARCH ────────────────────────────── */
    if (elVip) elVip.addEventListener('change', updateLog);
    if (elSearch) {
        var _searchTimer;
        elSearch.addEventListener('keyup', function() {
            clearTimeout(_searchTimer);
            _searchTimer = setTimeout(updateLog, 300);
        });
    }

    /* ── INIT ───────────────────────────────────────────── */
    applyPctColors();  // warnai pct-cell yang di-render PHP
    updateLog();

    // Reset buttons: semua tersembunyi kecuali area (tergantung RBAC)
    ['surveyor', 'comp', 'log'].forEach(function(k) {
        if (resetBtns[k]) resetBtns[k].style.display = 'none';
    });

    <?php if (!isset($user['group_user']) || ($user['group_user'] !== 'surveyor' && $user['group_user'] !== 'koordinator')): ?>
        if (resetBtns.area) resetBtns.area.style.display = 'inline-block';
    <?php else: ?>
        if (resetBtns.area) resetBtns.area.style.display = 'none';
    <?php endif ?>
});
</script>