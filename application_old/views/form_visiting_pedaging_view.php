<!DOCTYPE html>
<html>
<head>
    <title>Pedaging</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .form-container{margin-left:20px}.page-title{margin-left:10px}
        .custom-dropdown{position:relative}.dropdown-toggle{background:#fff;color:#333;border:1px solid #dee2e6;cursor:pointer;text-align:left;display:flex;justify-content:space-between;align-items:center}
        .dropdown-toggle:hover,.dropdown-toggle:focus{background:#f8f9fa;border-color:#0d6efd}.dropdown-toggle:disabled{background:#e9ecef;color:#6c757d;cursor:not-allowed}
        .dropdown-toggle::after{content:"?";font-size:12px}.farm-search-input{border:none;border-bottom:1px solid #dee2e6;background:#f8f9fa}
        .farm-search-input:focus{outline:2px solid #0d6efd;background:#fff}
        .dropdown-content{display:none;position:absolute;background:#fff;min-width:100%;max-height:200px;overflow-y:auto;border:1px solid #dee2e6;border-radius:0 0 .375rem .375rem;border-top:none;z-index:1000;box-shadow:0 .125rem .25rem rgba(0,0,0,.075)}
        .dropdown-content .farm-option{color:#333;padding:10px 12px;text-decoration:none;display:block;cursor:pointer;border-bottom:1px solid #eee}
        .dropdown-content .farm-option:hover{background:#f8f9fa}.show{display:block}.selected-farm{background:#fefefeff;border-color:#dee2e6}
        .integer-input,.currency-input,.varchar-input,.letters-only-input{position:relative;max-width:400px}
        .currency-prefix{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#666;pointer-events:none}
        .currency-input input{padding-left:30px}.loading{display:none;color:#666;font-style:italic}
        
        textarea::-webkit-scrollbar { display: none; }
        textarea { resize: none !important; }

        .other-question { display: none; }
        .question-hidden { display: none !important; }

        /* Style untuk pesan error per field */
        .field-error-msg {
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        /* Style untuk border merah pada input yang error */
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        /* Style untuk field yang auto-calculated */
        .auto-calculated {
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }
        
        .auto-calc-info {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* Style untuk info kapasitas (tambahan) */
        .farm-capacity-info {
            color: #0d6efd; /* Biru agar terlihat sebagai info */
            font-weight: bold;
        }

        /* Style untuk tombol refresh */
        #refreshKapasitasBtn {
            display: none; /* Sembunyi by default */
            padding: 0.1rem 0.4rem; 
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="page-title mb-4">
            <span id="dynamic-title">Pedaging</span> - <?=$nama_lokasi_header?>
        </h2>    
        <form method="post" id="pulletForm" class="form-container" novalidate>
            <input type="hidden" id="selected_tipe_ternak" name="tipe_ternak">
            <input type="hidden" id="selected_vip_farm" name="vip_farm" value="Tidak">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="location_address" id="location_address">
            <div id="pulletQuestions">
                <?php if(!empty($questions)):
                    $jenis_ternak_q=null;$other_questions=[];
                    foreach($questions as $q){
                        if(trim(strtolower($q['question_text']))==='jenis ternak pedaging')$jenis_ternak_q=$q;
                        else $other_questions[]=$q;
                    }
                    function renderQ($q,$isJT=false){
                        $req=!empty($q['required'])?'required':'';
                        $ast=!empty($q['required'])?'<span class="text-danger">*</span>':'';
                        $inp='q'.$q['questions_id'];
                        
                        $q_class = 'mb-4 question-group' . (!$isJT ? ' other-question' : '');
                        
                        $h='<div class="'.$q_class.'" data-field="'.$q['field_name'].'" data-question-id="'.$q['questions_id'].'"><label class="form-label fw-bold">'.$q['question_text'].' '.$ast.'</label>';
                        
                        if($q['type']=='select'){
                            if($q['field_name']=='nama_farm'){
                                $h.='<div class="custom-dropdown" style="max-width:400px">
                                <input type="hidden" name="'.$inp.'" id="namaFarmHidden_'.$q['questions_id'].'" data-field="'.$q['field_name'].'" '.$req.'>
                                <button type="button" onclick="toggleFarm(\''.$q['questions_id'].'\')" class="btn dropdown-toggle w-100 disabled" id="namaFarmToggle_'.$q['questions_id'].'" disabled>
                                <span id="selectedFarmText_'.$q['questions_id'].'">-- Pilih Nama Farm --</span></button>
                                <div id="namaFarmDropdown_'.$q['questions_id'].'" class="dropdown-content w-100">
                                <input type="text" placeholder="Cari..." id="farmSearchInput_'.$q['questions_id'].'" class="form-control farm-search-input" onkeyup="filterFarm(\''.$q['questions_id'].'\')">';
                                
                                if(!empty($q['options'])) {
                                    foreach($q['options'] as $o) {
                                        $kapasitas = (isset($o['kapasitas_farm']) && $o['kapasitas_farm'] !== null) ? $o['kapasitas_farm'] : '0';
                                        // PERBAIKAN: Tambahkan logika $vip_status
                                        $vip_status = (isset($o['vip_farm']) && trim($o['vip_farm']) === 'Ya') ? 'Ya' : 'Tidak';
                                        // PERBAIKAN: Tambahkan data-vip_farm ke div
                                        $h.='<div class="farm-option option-item" data-value="'.$o['option_text'].'" data-tipe="'.($o['tipe_ternak']??'').'" data-kapasitas="'.$kapasitas.'" data-vip_farm="'.$vip_status.'" onclick="selectFarm(this, \''.$q['questions_id'].'\')">'.$o['option_text'].'</div>';
                                    }
                                } else {
                                    $h.='<div class="farm-option text-muted text-center py-2">Tidak ada opsi tersedia</div>';
                                }
                                
                                $h.='</div></div>';
                            } else {
                                $oc=$isJT?'onchange="changeTipe(this.value)"':'';
                                $dis=$q['field_name']=='nama_peternak'?'disabled':'';
                                $h.='<select name="'.$inp.'" data-field="'.$q['field_name'].'" class="form-select" style="max-width:400px" '.$oc.' '.$dis.' '.$req.'>
                                    <option value="">-- Pilih --</option>';
                                    
                                if(!empty($q['options'])) {
                                    foreach($q['options'] as $o) {
                                        $h.='<option value="'.$o['option_text'].'" data-tipe="'.($o['tipe_ternak']??'').'" class="option-item">'.$o['option_text'].'</option>';
                                    }
                                } else {
                                    $h.='<option value="" disabled>Tidak ada opsi tersedia</option>';
                                }
                                
                                $h.='</select>';
                            }
                        }elseif($q['type']=='radio'&&!empty($q['options'])){
                            $h.='<div class="mt-2">';
                            foreach($q['options'] as $o){
                                $oid='radio_'.$q['questions_id'].'_'.($o['options_id']??rand());
                                $h.='<div class="form-check option-item" data-tipe="'.($o['tipe_ternak']??'').'">
                                <input class="form-check-input" type="radio" name="'.$inp.'" value="'.$o['option_text'].'" data-field="'.$q['field_name'].'" id="'.$oid.'" '.$req.'>
                                <label class="form-check-label" for="'.$oid.'">'.$o['option_text'].'</label></div>';
                            }$h.='</div>';
                        }elseif($q['type']=='textarea'){
                            $h.='<textarea name="'.$inp.'" class="form-control auto-resize-textarea" data-field="'.$q['field_name'].'" style="max-width:400px; resize: none; overflow: hidden; scrollbar-width: none; -ms-overflow-style: none;" rows="3" placeholder="Masukkan catatan..." oninput="autoResize(this)" '.$req.'></textarea>';
                        
                        }elseif($q['type']=='text'||$q['type']=='number'){
                            $cls='form-control';
                            $ph='Masukkan jawaban'; $ext=''; $w=''; $we='';
                            
                            if(isset($q['input_type'])){
                                switch($q['input_type']){
                                    case 'integer':
                                        $w='<div class="integer-input">';$we='</div>';$cls.=' integer-field';
                                        $ph='Masukkan angka bulat';
                                        $ext='onkeypress="return isInt(event)" oninput="formatInt(this)"';
                                        break;
                                    case 'currency':
                                        $w='<div class="currency-input"><span class="currency-prefix">Rp</span>';$we='</div>';
                                        $ph=' 0';
                                        $ext='oninput="formatCur(this)" onkeypress="return isNum(event)"';
                                        break;
                                    case 'letters_only':
                                        $w='<div class="letters-only-input">';$we='</div>';
                                        $ext='onkeypress="return isLet(event)" oninput="filterLet(this)"';
                                        break;
                                }
                            }
                            
                            if ($q['field_name'] === 'pedaging_harga_panen') {
                                $w = '<div class="currency-input"><span class="currency-prefix">Rp</span>';
                                $we = '</div>';
                                $ph = ' 0';
                                $ext = 'oninput="formatCur(this)" onkeypress="return isNum(event)"';
                            } 
                            // <-- (A.1) DIUBAH DI SINI
                            elseif(in_array($q['field_name'],['efektif_terisi_cp_pedaging', 'efektif_terisi_non_cp_pedaging'])){
                            // <-- (A.1) AKHIR PERUBAHAN
                                $ext='oninput="formatNum(this)" onkeypress="return(event.charCode>=48&&event.charCode<=57)"';
                                $ph = 'Masukkan angka bulat';
                            } 
                            elseif($q['field_name']=='umur_pedaging'){
                                // Khusus untuk umur_pedaging - make it readonly and auto-calculated
                                $ext='readonly';
                                $cls.=' auto-calculated';
                                $ph = 'Akan terisi otomatis';
                            }
                            elseif(in_array($q['field_name'],['deplesi_pedaging','intake_pedaging','pencapaian_berat_pedaging','keseragaman_pedaging','fcr_pedaging'])){
                                $ext='type="number" step="0.01" min="0"';
                                $ph = 'Masukkan angka';
                            }
                            
                            $h.=$w.'<input '.($ext?:'type="text"').' name="'.$inp.'" data-field="'.$q['field_name'].'" class="'.$cls.'" placeholder="'.$ph.'" style="max-width:400px" '.$req.'>'.$we;
                            
                            // Tambahkan info text khusus untuk umur_pedaging setelah input
                            if($q['field_name']=='umur_pedaging'){
                                $h.='<div class="auto-calc-info">* Umur dihitung otomatis berdasarkan tanggal chick-in</div>';
                            }

                            // **MODIFIKASI: Tambahkan div wrapper dan tombol refresh**
                            // <-- (A.2) DIUBAH DI SINI
                            if($q['field_name']=='efektif_terisi_non_cp_pedaging'){
                            // <-- (A.2) AKHIR PERUBAHAN
                                $h.='<div class="d-flex align-items-center" style="margin-top: 5px;">
                                        <div id="efektif_terisi_caption" class="auto-calc-info farm-capacity-info me-2">Kapasitas farm: -</div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshKapasitasBtn" onclick="refreshFarmData(this)" title="Refresh data farm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>';
                            }

                        }elseif($q['type']=='date'){
                            // Khusus untuk tanggal_chick_in_pedaging - tambahkan event listener
                            $dateExt = ($q['field_name'] == 'tanggal_chick_in_pedaging') ? 'onchange="calculateAge()"' : '';
                            $h.='<input type="date" name="'.$inp.'" data-field="'.$q['field_name'].'" class="form-control" style="max-width:400px" '.$dateExt.' '.$req.'>';
                        }elseif($q['type']=='checkbox'&&!empty($q['options'])){
                            $h.='<div class="mt-2">';
                            foreach($q['options'] as $o){
                                $oid='cb_'.$q['questions_id'].'_'.($o['options_id']??rand());
                                $h.='<div class="form-check option-item" data-tipe="'.($o['tipe_ternak']??'').'">
                                <input class="form-check-input" type="checkbox" name="'.$inp.'[]" value="'.$o['option_text'].'" id="'.$oid.'">
                                <label class="form-check-label" for="'.$oid.'">'.$o['option_text'].'</label></div>';
                            }$h.='</div>';
                        }
                        return $h.'</div>';
                    }
                    if($jenis_ternak_q)echo renderQ($jenis_ternak_q,true);
                    foreach($other_questions as $q)echo renderQ($q);
                else:?>
                    <div class="alert alert-info"><p class="mb-0 fst-italic">Tidak ada pertanyaan.</p></div>
                <?php endif?>
            </div>
            <div class="loading" id="loading">Loading...</div>
            <button type="submit" class="btn btn-primary px-4 py-2 mt-4" id="submitBtn">Submit</button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variabel global untuk menyimpan kapasitas farm
        let currentSelectedKapasitas = 0;

        // --- HELPER FUNCTIONS (UNCHANGED) ---
        const autoResize = (textarea) => { textarea.style.height = 'auto'; const minHeight = 80; const newHeight = Math.max(minHeight, textarea.scrollHeight); textarea.style.height = newHeight + 'px'; };
        const initResize = () => { document.querySelectorAll('.auto-resize-textarea').forEach(textarea => { textarea.style.height = '80px'; ['input', 'paste', 'focus'].forEach(event => textarea.addEventListener(event, () => setTimeout(() => autoResize(textarea), 10))); }); };
        const isLet=e=>{const c=e.which||e.keyCode;return[46,8,9,27,13,32].includes(c)||(c>=37&&c<=40)||((c>=65&&c<=90)||(c>=97&&c<=122))||(e.preventDefault(),false)};
        const filterLet=i=>i.value=i.value.replace(/[^a-zA-Z\s]/g,'').replace(/\s+/g,' ').trim();
        const isInt=e=>{const c=e.which||e.keyCode;return[46,8,9,27,13].includes(c)||(c>=37&&c<=40)||((c>=48&&c<=57)||(e.preventDefault(),false))};
        const formatInt=i=>{const v=i.value.replace(/[^\d]/g,'');i.value=v===''?'':parseInt(v,10).toLocaleString('en-US')};
        const formatCur=i=>{const v=i.value.replace(/[^\d]/g,'');i.value=v===''?'':parseInt(v).toLocaleString('en-US')};
        const isNum=e=>{const c=e.which||e.keyCode;return[46,8,9,27,13].includes(c)||(c>=48&&c<=57)};
        const formatNum=i=>{const v=i.value.replace(/\D/g,'');i.value=v!==''?parseInt(v).toLocaleString('en-US'):''};
        const toggleFarm=id=>{const d=document.getElementById(`namaFarmDropdown_${id}`),b=document.getElementById(`namaFarmToggle_${id}`);if(b.disabled)return;document.querySelectorAll('[id^="namaFarmDropdown_"]').forEach(dd=>{if(dd.id!==`namaFarmDropdown_${id}`)dd.classList.remove('show')});d.classList.toggle('show');if(d.classList.contains('show'))setTimeout(()=>document.getElementById(`farmSearchInput_${id}`).focus(),100)};
        const filterFarm=id=>{const f=document.getElementById(`farmSearchInput_${id}`).value.toUpperCase();const o=document.querySelectorAll(`#namaFarmDropdown_${id} .farm-option`);let v=false;o.forEach(opt=>{if(opt.classList.contains('text-muted'))return;const t=opt.textContent||opt.innerText;if(t.toUpperCase().indexOf(f)>-1&&!opt.hasAttribute('data-hidden-by-tipe')){opt.style.display='block';v=true}else{opt.style.display='none'}});const n=document.querySelector(`#namaFarmDropdown_${id} .farm-option.text-muted`);if(n){n.style.display=!v&&f?'block':'none'}};
        const closeFarm=id=>id?document.getElementById(`namaFarmDropdown_${id}`).classList.remove('show'):document.querySelectorAll('[id^="namaFarmDropdown_"]').forEach(d=>d.classList.remove('show'));
        const toggle=(en,tipe)=>{document.querySelectorAll('select[data-field="nama_peternak"]').forEach(s=>{s.disabled=!en;if(!en)s.value=''});document.querySelectorAll('[id^="namaFarmToggle_"]').forEach(b=>{b.disabled=!en;b.classList.toggle('disabled',!en);if(!en){const id=b.id.split('_')[1];const st=document.getElementById(`selectedFarmText_${id}`),hi=document.getElementById(`namaFarmHidden_${id}`);if(st)st.textContent='-- Pilih Nama Farm --';if(hi)hi.value='';b.classList.remove('selected-farm')}});document.querySelectorAll('[data-field*="strain"],[data-field*="pakan"]').forEach(q=>q.querySelectorAll('input,select,textarea').forEach(i=>{i.disabled=!en;if(!en){if(['radio','checkbox'].includes(i.type))i.checked=false;else i.value=''}}));if(en&&tipe)filterByTipe(tipe)};
        const filterByTipe=tipe=>{if(!tipe){document.querySelectorAll('.option-item').forEach(o=>{const qg=o.closest('.question-group');if(qg){const l=qg.querySelector('label');if(!l||!l.textContent.toLowerCase().includes('jenis ternak pedaging'))o.style.display='none'}});return}document.querySelectorAll('.option-item:not(.farm-option)').forEach(o=>{const ot=o.getAttribute('data-tipe')||o.dataset.tipe;o.style.display=(!ot||ot===tipe)?'block':'none'});document.querySelectorAll('.farm-option').forEach(o=>{const ot=o.getAttribute('data-tipe')||o.dataset.tipe;if(!ot||ot===tipe){o.style.display='block';o.removeAttribute('data-hidden-by-tipe')}else{o.style.display='none';o.setAttribute('data-hidden-by-tipe','true')}});document.querySelectorAll('select:not([data-field*="jenis_ternak"])').forEach(s=>{s.selectedIndex=0;s.querySelectorAll('option.option-item').forEach(o=>{const ot=o.getAttribute('data-tipe')||o.dataset.tipe;const sh=!ot||ot===tipe;o.style.display=sh?'block':'none';o.disabled=!sh})})};

        // --- FUNGSI PERHITUNGAN UMUR (UNCHANGED) ---
        function calculateAge() {
            const chickInInput = document.querySelector('input[data-field="tanggal_chick_in_pedaging"]');
            const ageInput = document.querySelector('input[data-field="umur_pedaging"]');
            
            if (!chickInInput || !ageInput) {
                console.warn('Input tanggal chick-in atau umur pedaging tidak ditemukan');
                return;
            }
            
            const chickInDate = chickInInput.value;
            
            if (!chickInDate) {
                ageInput.value = '';
                return;
            }
            
            try {
                const startDate = new Date(chickInDate);
                const today = new Date();
                
                // Reset time to avoid timezone issues
                startDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);
                
                // Calculate difference in days
                const timeDifference = today.getTime() - startDate.getTime();
                const daysDifference = Math.floor(timeDifference / (1000 * 3600 * 24));
                
                // Set the age value
                if (daysDifference >= 0) {
                    ageInput.value = daysDifference;
                } else {
                    ageInput.value = 0; // Jika tanggal chick-in di masa depan
                    console.warn('Tanggal chick-in tidak boleh di masa depan');
                }
                
                console.log(`Umur dihitung: ${daysDifference} hari (dari ${chickInDate} ke ${today.toDateString()})`);
                
            } catch (error) {
                console.error('Error calculating age:', error);
                ageInput.value = '';
            }
        }
        function startAgeCalculation() {
            calculateAge();
            setInterval(calculateAge, 60000);
        }

        // --- SISTEM VALIDASI (UNCHANGED) ---
        function validateSingleField(container) {
            if (container.classList.contains('question-hidden')) return true;
            const label = container.querySelector('label');
            if (!label || !label.querySelector('.text-danger')) return true;
            const questionText = label.innerText.replace('*', '').trim();
            let isValid = true;
            let errorMessage = '';
            let errorElement = null;
            const inputs = container.querySelectorAll('input, select, textarea');
            const customDropdown = container.querySelector('.custom-dropdown input[type="hidden"]');
            if (customDropdown) {
                isValid = customDropdown.value.trim() !== '';
                errorMessage = `${questionText} wajib dipilih.`;
                errorElement = container.querySelector('.dropdown-toggle');
            } else if (inputs.length > 0) {
                const input = inputs[0];
                if (input.type === 'radio') {
                    isValid = container.querySelector(`input[name="${input.name}"]:checked`) !== null;
                    errorMessage = `${questionText} wajib dipilih.`;
                    errorElement = container.querySelector('.form-check');
                } else if (input.tagName.toLowerCase() === 'select') {
                    isValid = input.value.trim() !== '';
                    errorMessage = `${questionText} wajib dipilih.`;
                    errorElement = input;
                } else {
                    isValid = input.value.trim() !== '';
                    errorMessage = `${questionText} wajib diisi.`;
                    errorElement = input;
                }
            }
            const existingError = container.querySelector('.field-error-msg');
            if (!isValid) {
                if (!existingError) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'field-error-msg text-danger';
                    errorDiv.textContent = errorMessage;
                    container.appendChild(errorDiv);
                }
                if (errorElement) errorElement.classList.add('is-invalid');
            } else {
                if (existingError) existingError.remove();
                if (errorElement) errorElement.classList.remove('is-invalid');
            }
            return isValid;
        }
        function validateAllRequiredFields() {
            let allValid = true;
            const errorFields = [];
            document.querySelectorAll('.question-group').forEach(container => {
                if (container.offsetParent !== null && !container.classList.contains('question-hidden')) {
                    if (!validateSingleField(container)) {
                        allValid = false;
                        errorFields.push(container);
                    }
                }
            });
            if (!allValid && errorFields.length > 0) {
                errorFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return allValid;
        }
        function addRealTimeValidation() {
            document.querySelectorAll('.question-group').forEach(container => {
                const inputs = container.querySelectorAll('input:not([type="hidden"]), select, textarea');
                inputs.forEach(input => {
                    const eventType = (input.type === 'radio' || input.tagName.toLowerCase() === 'select') ? 'change' : 'input';
                    input.removeEventListener(eventType, handleInputValidation);
                    input.addEventListener(eventType, handleInputValidation);
                });
            });
        }
        function handleInputValidation(event) {
            const container = event.target.closest('.question-group');
            if (container) {
                if (event.target.value.trim() !== '' || (event.target.type === 'radio' && event.target.checked)) {
                    const errorMsg = container.querySelector('.field-error-msg');
                    if (errorMsg) errorMsg.remove();
                    event.target.classList.remove('is-invalid');
                }
            }
        }
        function clearAllValidationErrors() {
            document.querySelectorAll('.field-error-msg').forEach(msg => msg.remove());
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }

       // ... (Kode JavaScript sebelumnya) ...

// --- FUNGSI BARU UNTUK REFRESH DATA ---

/**
 * Helper untuk mereset pilihan farm dan kapasitas
 */
function resetSelectedFarm(questionId) {
    const hiddenInput = document.getElementById(`namaFarmHidden_${questionId}`);
    const button = document.getElementById(`namaFarmToggle_${questionId}`);
    const selectedText = document.getElementById(`selectedFarmText_${questionId}`);
    
    if (hiddenInput) hiddenInput.value = '';
    if (selectedText) selectedText.textContent = '-- Pilih Nama Farm --';
    if (button) button.classList.remove('selected-farm');
    
    // Reset juga caption kapasitas
    const capacityDiv = document.getElementById('efektif_terisi_caption');
    if (capacityDiv) {
        capacityDiv.textContent = 'Kapasitas farm: -';
    }
    currentSelectedKapasitas = 0;
    
    // Reset VIP farm
    const vipFarmInput = document.getElementById('selected_vip_farm');
    if (vipFarmInput) {
        vipFarmInput.value = 'Tidak';
    }
}

/**
 * Fungsi utama untuk refresh data farm via AJAX (FINAL FIX)
 */
function refreshFarmData(button) {
    const tipeTernak = document.getElementById('selected_tipe_ternak').value;
    if (!tipeTernak) {
        Swal.fire('Info', 'Pilih Jenis Ternak Pedaging terlebih dahulu.', 'info');
        return;
    }

    const farmDropdownGroup = document.querySelector('.question-group[data-field="nama_farm"]');
    if (!farmDropdownGroup) {
        console.error('Tidak dapat menemukan grup pertanyaan "nama_farm".');
        return;
    }
    const questionId = farmDropdownGroup.dataset.questionId;
    const dropdownContent = document.getElementById(`namaFarmDropdown_${questionId}`);
    const hiddenInput = document.getElementById(`namaFarmHidden_${questionId}`);
    
    // 1. Ambil nama farm yang saat ini terpilih sebelum request
    const currentSelectedFarm = hiddenInput ? hiddenInput.value : '';

    // Tampilkan loading
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    const oldHtml = dropdownContent.innerHTML;
    dropdownContent.innerHTML = '<div class="farm-option text-muted text-center py-2">Memuat data baru...</div>';

    const x = new XMLHttpRequest();
    x.open('POST', "<?=site_url('Visiting_Pedaging_Controller/ajax_refresh_farm_options')?>", true);
    x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    x.onreadystatechange = function() {
        if (x.readyState === 4) {
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
            
            if (x.status === 200) {
                try {
                    const responseData = JSON.parse(x.responseText);
                    const newOptions = responseData.options || [];
                    const farmToRestore = responseData.selected_farm || '';
                    
                    dropdownContent.innerHTML = ''; 
                    
                    // 2. Tambahkan input search lagi
                    const searchInput = document.createElement('input');
                    searchInput.type = 'text';
                    searchInput.placeholder = 'Cari...';
                    searchInput.id = `farmSearchInput_${questionId}`;
                    searchInput.className = 'form-control farm-search-input';
                    searchInput.onkeyup = () => filterFarm(questionId);
                    dropdownContent.appendChild(searchInput);
                    
                    let farmFoundAndSelected = false;
                    let elementToRestore = null;

                    // 3. Bangun ulang opsi-opsi
                    if (newOptions && newOptions.length > 0) {
                        newOptions.forEach(o => {
                            const kapasitas = (o.kapasitas_farm !== null) ? o.kapasitas_farm : '0';
                            const vip_status = (o.vip_farm && o.vip_farm.trim() === 'Ya') ? 'Ya' : 'Tidak';
                            
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'farm-option option-item';
                            optionDiv.dataset.value = o.option_text;
                            optionDiv.dataset.tipe = o.tipe_ternak || '';
                            optionDiv.dataset.kapasitas = kapasitas;
                            optionDiv.dataset.vip_farm = vip_status; 
                            
                            // Penting: Gunakan fungsi selectFarm yang sudah ada
                            optionDiv.onclick = () => selectFarm(optionDiv, questionId); 
                            optionDiv.textContent = o.option_text;
                            dropdownContent.appendChild(optionDiv);

                            // Simpan referensi elemen jika ini adalah farm yang harus dipulihkan
                            if (o.option_text === farmToRestore) {
                                elementToRestore = optionDiv;
                                farmFoundAndSelected = true;
                            }
                        });
                    } else {
                        const noOptionDiv = document.createElement('div');
                        noOptionDiv.className = 'farm-option text-muted text-center py-2';
                        noOptionDiv.textContent = 'Tidak ada opsi tersedia';
                        dropdownContent.appendChild(noOptionDiv);
                    }

                    // 4. Panggil selectFarm HANYA SEKALI setelah semua elemen dibuat, jika ditemukan
                    if (elementToRestore) {
                        selectFarm(elementToRestore, questionId); 
                    } else {
                        // Jika farm lama tidak ada lagi, reset
                        resetSelectedFarm(questionId);
                    }

                    // 5. Beri notifikasi yang sesuai
                    if (farmToRestore && !farmFoundAndSelected) {
                        Swal.fire('Perhatian', `Data farm berhasil diperbarui. Farm **${farmToRestore}** tidak ditemukan di daftar baru. Silakan pilih farm lain.`, 'warning');
                    } else {
                        Swal.fire('Sukses', `Data farm berhasil diperbarui. ${farmFoundAndSelected ? 'Farm **' + farmToRestore + '** tetap terpilih.' : ''}`, 'success');
                    }

                } catch (e) {
                    console.error('Gagal parse JSON:', e);
                    Swal.fire('Error', 'Gagal memproses data baru.', 'error');
                    dropdownContent.innerHTML = oldHtml;
                }
            } else {
                Swal.fire('Error', 'Gagal mengambil data farm dari server.', 'error');
                dropdownContent.innerHTML = oldHtml;
            }
        }
    };
    
    // 6. Kirim nilai nama farm yang saat ini terpilih ke Controller
    const data = 'tipe_ternak=' + encodeURIComponent(tipeTernak) + 
                 '&selected_nama_farm=' + encodeURIComponent(currentSelectedFarm);
    x.send(data);
}

        // --- FUNGSI UTAMA ---

        const selectFarm = (element, id) => {
            const name = element.dataset.value;
            const kapasitas = element.dataset.kapasitas;
            // PERBAIKAN: Ambil data vip_farm
            const vip_status = element.dataset.vip_farm;

            if (name === 'Tidak ada opsi tersedia') return;
            // ... (kode selectFarm lainnya tidak berubah) ...
            
            const hiddenInput = document.getElementById(`namaFarmHidden_${id}`);
            const button = document.getElementById(`namaFarmToggle_${id}`);
            
            hiddenInput.value = name;
            document.getElementById(`selectedFarmText_${id}`).textContent = name;
            button.classList.add('selected-farm');
            closeFarm(id);
            document.getElementById(`farmSearchInput_${id}`).value = '';
            document.querySelectorAll(`#namaFarmDropdown_${id} .farm-option`).forEach(o => {
                if (!o.hasAttribute('data-hidden-by-tipe') && !o.classList.contains('text-muted')) {
                    o.style.display = 'block';
                }
            });
            
            const container = button.closest('.question-group');
            if (container) {
                const errorMsg = container.querySelector('.field-error-msg');
                if (errorMsg) errorMsg.remove();
                button.classList.remove('is-invalid');
            }

            // PERBAIKAN: Set VIP farm status
            const vipFarmInput = document.getElementById('selected_vip_farm');
            if (vipFarmInput) {
                vipFarmInput.value = vip_status;
            }

            const capacityDiv = document.getElementById('efektif_terisi_caption');
            if (capacityDiv) {
                const kap_int = parseInt(kapasitas, 10); 
                if (!isNaN(kap_int)) { 
                    currentSelectedKapasitas = kap_int; 
                    const formattedKapasitas = kap_int.toLocaleString('en-US');
                    capacityDiv.textContent = `Kapasitas farm: ${formattedKapasitas}`;
                } else {
                    currentSelectedKapasitas = 0; 
                    capacityDiv.textContent = 'Kapasitas farm: -';
                }
            }
        };

        const manageQuestionVisibility = (tipe) => {
            // ... (kode manageQuestionVisibility tidak berubah) ...
            document.querySelectorAll('.question-group[data-field]').forEach(questionDiv => {
                const fieldName = questionDiv.getAttribute('data-field');
                const hiddenFields = { 'Grower': ['pedaging_harga_panen'] };
                const fieldsToHide = hiddenFields[tipe] || [];
                
                if (fieldsToHide.includes(fieldName)) {
                    questionDiv.classList.add('question-hidden');
                    const inputs = questionDiv.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.type === 'radio' || input.type === 'checkbox') input.checked = false;
                        else input.value = '';
                        if (input.hasAttribute('required')) {
                            input.setAttribute('data-was-required', 'true');
                            input.removeAttribute('required');
                        }
                        const errorMsg = questionDiv.querySelector('.field-error-msg');
                        if(errorMsg) errorMsg.remove();
                        input.classList.remove('is-invalid');
                    });
                } else {
                    questionDiv.classList.remove('question-hidden');
                    const inputs = questionDiv.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        if (input.hasAttribute('data-was-required')) {
                            input.setAttribute('required', 'required');
                            input.removeAttribute('data-was-required');
                        }
                    });
                }
            });
            setTimeout(calculateAge, 100);
        };

        const changeTipe = tipe => {
            clearAllValidationErrors();
            document.getElementById('selected_tipe_ternak').value = tipe;
            document.getElementById('dynamic-title').textContent = tipe || 'Pedaging';

            // **MODIFIKASI: Tampilkan/sembunyikan tombol refresh**
            const refreshBtn = document.getElementById('refreshKapasitasBtn');
            
            const otherQuestions = document.querySelectorAll('.other-question');
            if (!tipe) {
                toggle(false);
                otherQuestions.forEach(q => q.style.display = 'none');
                if (refreshBtn) refreshBtn.style.display = 'none'; // Sembunyikan
                return;
            }

            if (refreshBtn) refreshBtn.style.display = 'inline-block'; // Tampilkan

            otherQuestions.forEach(q => q.style.display = 'block');
            // ... (sisa kode changeTipe tidak berubah) ...
            document.querySelectorAll('select[data-field="nama_peternak"],[data-field*="strain"] select,[data-field*="pakan"] select').forEach(s=>s.selectedIndex=0);
            document.querySelectorAll('[id^="namaFarmToggle_"]').forEach(b=>{
                const id=b.id.split('_')[1];
                document.getElementById(`selectedFarmText_${id}`).textContent='-- Pilih Nama Farm --';
                document.getElementById(`namaFarmHidden_${id}`).value='';
                b.classList.remove('selected-farm');
                closeFarm(id);
            });
            toggle(true, tipe);

            const capacityDiv = document.getElementById('efektif_terisi_caption');
            if (capacityDiv) {
                capacityDiv.textContent = 'Kapasitas farm: -';
            }
            currentSelectedKapasitas = 0;

            // PERBAIKAN: Reset VIP Farm
            const vipFarmInput = document.getElementById('selected_vip_farm');
            if (vipFarmInput) {
                vipFarmInput.value = 'Tidak';
            }
            
            manageQuestionVisibility(tipe);
            
            document.getElementById('loading').style.display='block';
            document.getElementById('submitBtn').disabled=true;
            const x=new XMLHttpRequest();
            x.open('POST','<?=site_url('Visiting_Pedaging_Controller/get_options_by_livestock_type')?>',true);
            x.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            x.onreadystatechange=()=>{
                if(x.readyState===4&&x.status===200){
                    document.getElementById('loading').style.display='none';
                    document.getElementById('submitBtn').disabled=false;
                    const js=document.querySelector('select[onchange*="changeTipe"]');
                    if(js&&js.value!==tipe)js.value=tipe;
                    
                    // Recalculate age after AJAX complete
                    setTimeout(calculateAge, 200);
                }
            };
            x.send('livestock_type='+encodeURIComponent(tipe));
        };

        // --- EVENT LISTENERS ---
        document.addEventListener('DOMContentLoaded', () => {
            initResize();
            toggle(false);
            addRealTimeValidation();
            startAgeCalculation();
            
            document.addEventListener('click', e => { if (!e.target.closest('.custom-dropdown')) closeFarm() });
            
            // **MODIFIKASI: Event listener SUBMIT**
            document.getElementById('pulletForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                calculateAge();
                
                // 1. Jalankan validasi wajib isi
                if (!validateAllRequiredFields()) {
                    return;
                }

                // 2. BARU: Jalankan validasi kapasitas
                if (currentSelectedKapasitas > 0) {
                    // <-- (B) AWAL PERUBAHAN
                    const cpInput = document.querySelector('input[data-field="efektif_terisi_cp_pedaging"]');
                    const nonCpInput = document.querySelector('input[data-field="efektif_terisi_non_cp_pedaging"]');
                    let errorInput = null;
                    let totalTerisi = 0;

                    if (cpInput || nonCpInput) {
                        const valCP = cpInput ? parseInt(cpInput.value.replace(/,/g, ''), 10) : 0;
                        const valNonCP = nonCpInput ? parseInt(nonCpInput.value.replace(/,/g, ''), 10) : 0;
                        
                        totalTerisi = (isNaN(valCP) ? 0 : valCP) + (isNaN(valNonCP) ? 0 : valNonCP);
                        errorInput = nonCpInput || cpInput; // Targetkan error ke field terakhir
                        
                        if (!isNaN(totalTerisi) && totalTerisi > currentSelectedKapasitas) {
                            const totalTerisiFormatted = totalTerisi.toLocaleString('en-US'); // <-- DIUBAH
                            const kapasitasFormatted = currentSelectedKapasitas.toLocaleString('en-US');
                            const adminFarmUrl = "<?=site_url('Admin_Controller/Farm')?>";

                            Swal.fire({
                                icon: 'error',
                                title: 'Kapasitas Terlampaui',
                                html: `Jumlah total "Efektif Terisi" (<b>${totalTerisiFormatted}</b>) melebihi kapasitas farm (<b>${kapasitasFormatted}</b>).<br><br>Apakah Anda ingin memperbarui data kapasitas farm?`, // <-- DIUBAH
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Perbarui Kapasitas',
                                cancelButtonText: 'Tidak, Perbaiki Input',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.open(adminFarmUrl, '_blank');
                                }
                            });
                            
                            if (errorInput) {
                                errorInput.classList.add('is-invalid');
                                errorInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            
                            return; // Hentikan submit
                        }
                    }
                    // <-- (B) AKHIR PERUBAHAN
                }
                // --- AKHIR VALIDASI KAPASITAS ---

                
                // 3. Lanjutkan ke proses submit jika lolos
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                document.getElementById('loading').style.display = 'block';
                
                try {
                    const position = await getCurrentLocation();
                    const { latitude, longitude } = position.coords;
                    const formattedLat = parseFloat(latitude).toFixed(7);
                    const formattedLon = parseFloat(longitude).toFixed(7);
                    
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${formattedLat}&lon=${formattedLon}&zoom=18&addressdetails=1&accept-language=id`, {
                                method: 'GET',
                                headers: { 'User-Agent': 'Mozilla/5.0', 'Accept': 'application/json', 'Accept-Language': 'id' },
                                referrerPolicy: 'no-referrer'
                            }
                        );
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        const data = await response.json();
                        if (!data.display_name) throw new Error('No address found');

                        document.getElementById('latitude').value = formattedLat;
                        document.getElementById('longitude').value = formattedLon;
                        document.getElementById('location_address').value = data.display_name;
                        
                        document.querySelectorAll('.currency-input input, .integer-field').forEach(i => {
                            i.value = i.value.replace(/,/g, '');
                        });
                        
                        e.target.submit();
                        
                    } catch (error) {
                        console.error('Address fetch error:', error);
                        throw new Error(`Gagal mendapatkan alamat: ${error.message}`);
                    }
                } catch (error) {
                    console.error('Submit Error:', error);
                    alert(error.message); 
                    submitBtn.disabled = false;
                    document.getElementById('loading').style.display = 'none';
                }
            });
        });

        function getCurrentLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    return reject(new Error('Geolocation tidak didukung oleh browser Anda.'));
                }
                navigator.geolocation.getCurrentPosition(resolve, (err) => {
                    let msg = 'Gagal mendapatkan lokasi: ';
                    switch(err.code) {
                        case err.PERMISSION_DENIED: msg += 'Izin akses lokasi ditolak.'; break;
                        case err.POSITION_UNAVAILABLE: msg += 'Informasi lokasi tidak tersedia.'; break;
                        case err.TIMEOUT: msg += 'Waktu permintaan lokasi habis.'; break;
                        default: msg += 'Terjadi kesalahan tidak diketahui.'; break;
                    }
                    reject(new Error(msg));
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            });
        }
        
        window.addEventListener('resize', () => document.querySelectorAll('.auto-resize-textarea').forEach(autoResize));
    </script>
</body>
</html>