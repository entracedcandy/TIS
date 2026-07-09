<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Visiting Lainnya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { margin-left: 20px; }
        .page-title { margin-left: 10px; }
        .question-group { max-width: 500px; }

        /* --- STYLES FOR CUSTOM DROPDOWN --- */
        .custom-dropdown { position: relative; }
        .dropdown-toggle { background: #fff; border: 1px solid #dee2e6; cursor: pointer; text-align: left; display: flex; justify-content: space-between; align-items: center; }
        .dropdown-toggle::after { content: "?"; font-size: 12px; }
        .farm-search-input { border: none; border-bottom: 1px solid #dee2e6; }
        .dropdown-content { display: none; position: absolute; background: #fff; min-width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; z-index: 1000; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
        .dropdown-content .farm-option { padding: 10px 12px; display: block; cursor: pointer; border-bottom: 1px solid #eee; }
        .dropdown-content .farm-option:hover { background: #f8f9fa; }
        .show { display: block; }
        .selected-farm { border-color: #0d6efd; }
        
        /* --- GENERAL STYLES --- */
        .loading-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.7); z-index: 9999;
            justify-content: center; align-items: center; flex-direction: column;
        }
        .field-error-msg { font-size: 0.875em; margin-top: 0.25rem; }
        .is-invalid { border-color: #dc3545 !important; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <h2 class="page-title mb-4">
            Visiting Lainnya - <?= htmlspecialchars($nama_lokasi_header ?? 'Area Anda') ?>
        </h2>

        <form method="post" action="<?= site_url('Visiting_Lainnya_Controller/index') ?>" id="visitingLainnyaForm" class="form-container" novalidate>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="location_address" id="location_address">

            <?php
            if (!empty($questions)) {
                function render_question_custom($q) {
                    $name = 'q' . $q['questions_id'];
                    $required = !empty($q['required']) ? 'required' : '';
                    $required_star = !empty($q['required']) ? '<span class="text-danger">*</span>' : '';
                    $html = '';

                    $html .= "<div class='mb-4 question-group' data-field='{$q['field_name']}'>";
                    $html .= "<label class='form-label fw-bold'>{$q['question_text']} {$required_star}</label>";

                    // LOGIKA UNTUK CUSTOM DROPDOWN 'NAMA FARM'
                    // PERBAIKAN: Menggunakan $q['type'] bukan $q['question_type']
                    if (isset($q['type']) && $q['type'] == 'select' && $q['field_name'] == 'nama_farm') {
                        $html .= '<div class="custom-dropdown">
                            <input type="hidden" name="'.$name.'" id="namaFarmHidden_'.$q['questions_id'].'" '.$required.'>
                            <button type="button" onclick="toggleFarmDropdown(\''.$q['questions_id'].'\')" id="namaFarmToggle_'.$q['questions_id'].'" class="btn dropdown-toggle w-100">
                                <span id="selectedFarmText_'.$q['questions_id'].'">-- Pilih Nama Farm --</span>
                            </button>
                            <div id="namaFarmDropdown_'.$q['questions_id'].'" class="dropdown-content w-100">
                                <input type="text" placeholder="Cari..." id="farmSearchInput_'.$q['questions_id'].'" class="form-control farm-search-input" onkeyup="filterFarmOptions(\''.$q['questions_id'].'\')">';
                        
                        if (!empty($q['options'])) {
                            foreach ($q['options'] as $o) {
                                $html .= '<div class="farm-option" onclick="selectFarm(\''.$q['questions_id'].'\', \'' . htmlspecialchars($o['option_text'], ENT_QUOTES) . '\')">' . htmlspecialchars($o['option_text']) . '</div>';
                            }
                        } else {
                            $html .= '<div class="farm-option text-muted">Tidak ada opsi</div>';
                        }
                        $html .= '</div></div>';

                    } else { // RENDER ELEMENT FORMULIR STANDAR
                        // PERBAIKAN: Menggunakan $q['type'] dan menambahkan isset() untuk keamanan
                        $questionType = isset($q['type']) ? $q['type'] : 'text';

                        switch ($questionType) {
                            case 'select':
                                $html .= "<select name='{$name}' id='{$name}' class='form-select' {$required}>";
                                $html .= "<option value=''>-- Pilih Opsi --</option>";
                                if (!empty($q['options'])) {
                                    foreach ($q['options'] as $o) {
                                    // Cek apakah 'option_value' ada. Jika tidak, gunakan 'option_text' sebagai fallback.
                                    $option_val = isset($o['option_value']) ? $o['option_value'] : $o['option_text'];
                                    $html .= "<option value='" . htmlspecialchars($option_val) . "'>" . htmlspecialchars($o['option_text']) . "</option>";                                    }
                                }
                                $html .= "</select>";
                                break;
                            case 'textarea':
                                $html .= "<textarea name='{$name}' id='{$name}' class='form-control' rows='3' {$required}></textarea>";
                                break;
                            case 'date':
                                $html .= "<input type='date' name='{$name}' id='{$name}' class='form-control' {$required}>";
                                break;
                            default: // text, number, etc.
                                $extra_attrs = '';
                                if (isset($q['input_type']) && $q['input_type'] === 'integer') {
                                    $extra_attrs = 'oninput="formatInteger(this)" onkeypress="return isNumberKey(event)" placeholder="Masukkan angka bulat"';
                                }
                                $html .= "<input type='text' name='{$name}' id='{$name}' class='form-control' {$extra_attrs} {$required}>";
                                break;
                        }
                    }
                    $html .= "</div>";
                    return $html;
                }

                foreach ($questions as $q) {
                    echo render_question_custom($q);
                }
            } else {
                echo '<div class="alert alert-info">Tidak ada pertanyaan yang tersedia.</div>';
            }
            ?>

            <button type="submit" class="btn btn-primary px-4 py-2 mt-4" id="submitBtn">Submit</button>
        </form>
    </div>

    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
        <p class="mt-2 text-primary">Memproses data...</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- FUNGSI HELPER DASAR ---
        const isNumberKey = e => { const c = e.which || e.keyCode; return [46, 8, 9, 27, 13].includes(c) || (c >= 48 && c <= 57); };
        const formatInteger = i => { const v = i.value.replace(/[^\d]/g, ''); i.value = v === '' ? '' : parseInt(v).toLocaleString('id-ID'); };

        // --- FUNGSI UNTUK CUSTOM DROPDOWN ---
        function toggleFarmDropdown(id) {
            document.getElementById(`namaFarmDropdown_${id}`).classList.toggle('show');
        }

        function filterFarmOptions(id) {
            const input = document.getElementById(`farmSearchInput_${id}`);
            const filter = input.value.toUpperCase();
            const dropdown = document.getElementById(`namaFarmDropdown_${id}`);
            const options = dropdown.getElementsByClassName('farm-option');
            for (let i = 0; i < options.length; i++) {
                const txtValue = options[i].textContent || options[i].innerText;
                options[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }

        function selectFarm(id, value) {
            document.getElementById(`namaFarmHidden_${id}`).value = value;
            document.getElementById(`selectedFarmText_${id}`).textContent = value;
            
            const button = document.getElementById(`namaFarmToggle_${id}`);
            button.classList.add('selected-farm');
            
            // Hapus error jika ada
            const container = button.closest('.question-group');
            container.querySelector('.field-error-msg')?.remove();
            container.querySelector('.is-invalid')?.classList.remove('is-invalid');
            
            closeAllDropdowns();
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
        }

        // Tutup dropdown jika klik di luar
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle, .dropdown-toggle *')) {
                closeAllDropdowns();
            }
        }

        // --- VALIDASI & SUBMIT ---
        function validateForm() {
            let isValid = true;
            const form = document.getElementById('visitingLainnyaForm');

            form.querySelectorAll('.field-error-msg').forEach(e => e.remove());
            form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));

            form.querySelectorAll('[required]').forEach(input => {
                const container = input.closest('.question-group');
                if (container && container.offsetParent !== null) {
                    let hasValue = input.value.trim() !== '';
                    
                    if (!hasValue) {
                        isValid = false;
                        let errorElement = input;
                        
                        // Khusus untuk custom dropdown, beri border merah pada tombolnya
                        if (input.type === 'hidden' && container.querySelector('.custom-dropdown')) {
                            errorElement = container.querySelector('.dropdown-toggle');
                        }
                        
                        errorElement.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'field-error-msg text-danger';
                        errorDiv.textContent = 'Field ini wajib diisi.';
                        container.appendChild(errorDiv);
                    }
                }
            });
            return isValid;
        }

        document.getElementById('visitingLainnyaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm()) {
                alert('Harap isi semua field yang wajib diisi.');
                return;
            }
            
            document.getElementById('loadingOverlay').style.display = 'flex';

            try {
                // 1. DAPATKAN LOKASI (GPS)
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true, timeout: 10000, maximumAge: 0
                    });
                });
                const { latitude, longitude } = position.coords;
                
                // Siapkan variabel lat/lon
                const lat = latitude.toFixed(7);
                const lon = longitude.toFixed(7);
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;
                
                // 2. DAPATKAN ALAMAT (NOMINATIM) - BLOK INI DIPERBAIKI
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1&accept-language=id`, {
                            method: 'GET',
                            headers: { 
                                'User-Agent': 'Mozilla/5.0', // <-- KUNCI PERBAIKAN
                                'Accept': 'application/json', 
                                'Accept-Language': 'id' 
                            },
                            referrerPolicy: 'no-referrer'
                        }
                    );

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const data = await response.json();
                    
                    if (!data.display_name) {
                        throw new Error('Alamat tidak ditemukan dari API.');
                    }

                    // Alamat berhasil didapat
                    document.getElementById('location_address').value = data.display_name;

                    // 3. JIKA ALAMAT SUKSES, BARU LANJUTKAN SUBMIT
                    document.querySelectorAll('input[oninput*="formatInteger"]').forEach(input => {
                        input.value = input.value.replace(/\./g, '');
                    });
                    
                    this.submit(); // Kirim form

                } catch (addrError) {
                    // Jika fetch GAGAL, tangkap di sini
                    console.warn('Gagal mendapatkan alamat:', addrError);
                    document.getElementById('location_address').value = 'Gagal memuat alamat';
                    
                    // Beri tahu pengguna apa masalahnya
                    alert('Gagal mendapatkan alamat: ' + addrError.message + '. Harap coba lagi.');
                    
                    // Hentikan loading overlay agar tidak macet
                    document.getElementById('loadingOverlay').style.display = 'none'; 
                    // Jangan panggil this.submit()
                }

            } catch (error) { // Ini adalah catch untuk Geolocation (GPS)
                let errorMsg = 'Gagal memproses: ';
                if (error.code === 1) errorMsg += 'Izin akses lokasi ditolak.';
                else if (error.code === 3) errorMsg += 'Waktu permintaan lokasi habis.';
                else errorMsg += 'Pastikan GPS dan izin lokasi aktif.';
                
                alert(errorMsg);
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        });
    </script>
</body>
</html>