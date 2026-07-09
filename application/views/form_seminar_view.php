<!DOCTYPE html>
<html>
<head>
    <title>Seminar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container{margin-left:20px}
        .page-title{margin-left:10px}
        .question-group{margin-bottom:15px}
        .custom-dropdown{position:relative;max-width:400px}
        .dropdown-toggle{background:#fff;color:#333;border:1px solid #dee2e6;cursor:pointer;text-align:left;display:flex;justify-content:space-between;align-items:center}
        .dropdown-toggle:hover,.dropdown-toggle:focus{background:#f8f9fa;border-color:#0d6efd}
        .dropdown-toggle::after{content:"▼";font-size:12px}
        .search-input{border:none;border-bottom:1px solid #dee2e6;background:#f8f9fa}
        .search-input:focus{outline:2px solid #0d6efd;background:#fff}
        .dropdown-content{display:none;position:absolute;background:#fff;min-width:100%;max-height:200px;overflow-y:auto;border:1px solid #dee2e6;border-radius:0 0 .375rem .375rem;border-top:none;z-index:1000;box-shadow:0 .125rem .25rem rgba(0,0,0,.075)}
        .dropdown-content .option-item{color:#333;padding:10px 12px;text-decoration:none;display:block;cursor:pointer;border-bottom:1px solid #eee}
        .dropdown-content .option-item:hover{background:#f8f9fa}
        .show{display:block}
        .selected-option{background:#fff;border-color:#dee2e6}
        .invalid-field{border-color:#dc3545 !important;}
        .error-message{color:#dc3545;font-size:0.875em;margin-top:0.25rem;}
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="page-title mb-4">Seminar</h2>
        <form method="post" id="seminarForm" class="form-container" onsubmit="return validateForm()">
            <?php if (!empty($questions)): 
                foreach ($questions as $q): ?>
                    <div class="question-group">
                        <label class="form-label fw-bold mb-1">
                            <?= $q['question_text'] ?>
                            <?= !empty($q['required']) ? '<span class="text-danger">*</span>' : '' ?>
                        </label>

                        <?php if ($q['type'] == 'radio' && !empty($q['options'])): ?>
                            <div class="mt-1">
                                <?php foreach ($q['options'] as $opt): ?>
                                    <div class="form-check my-1">
                                        <input class="form-check-input" type="radio" name="q<?= $q['questions_id'] ?>" 
                                               value="<?= $opt['option_text'] ?>" id="r_<?= $q['questions_id'] ?>_<?= $opt['options_id'] ?? rand() ?>"
                                               <?= !empty($q['required']) ? 'required' : '' ?> 
                                               data-required="<?= !empty($q['required']) ? 'true' : 'false' ?>"
                                               onchange="toggleOther(<?= $q['questions_id'] ?>, '<?= $opt['option_text'] ?>')">
                                        <label class="form-check-label" for="r_<?= $q['questions_id'] ?>_<?= $opt['options_id'] ?? rand() ?>">
                                            <?= $opt['option_text'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <div id="other_<?= $q['questions_id'] ?>" class="mt-2 d-none">
                                    <input type="text" name="q<?= $q['questions_id'] ?>_other" class="form-control" 
                                           placeholder="Please specify..." style="max-width:400px">
                                </div>
                            </div>

                        <?php elseif ($q['type'] == 'select'): ?>
                            <div class="custom-dropdown mt-1">
                                <input type="hidden" name="q<?= $q['questions_id'] ?>" id="h_<?= $q['questions_id'] ?>" 
                                       data-custom-dropdown="true" data-required="<?= !empty($q['required']) ? 'true' : 'false' ?>"
                                       <?= !empty($q['required']) ? 'required' : '' ?>>
                                <button type="button" onclick="toggleDropdown(<?= $q['questions_id'] ?>)" 
                                        class="btn dropdown-toggle w-100" id="t_<?= $q['questions_id'] ?>">
                                    <span id="s_<?= $q['questions_id'] ?>">-- Pilih Jawaban --</span>
                                </button>
                                <div id="d_<?= $q['questions_id'] ?>" class="dropdown-content w-100">
                                    <input type="text" placeholder="Search..." id="i_<?= $q['questions_id'] ?>" 
                                           class="form-control search-input" onkeyup="filterOptions(<?= $q['questions_id'] ?>)">
                                    <?php if (!empty($q['options'])): ?>
                                        <?php foreach ($q['options'] as $opt): ?>
                                            <div class="option-item" onclick="selectOption(<?= $q['questions_id'] ?>, '<?= $opt['option_text'] ?>')">
                                                <?= $opt['option_text'] ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="option-item text-muted" style="cursor: default; font-style: italic;">
                                            Tidak ada opsi tersedia
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div id="other_<?= $q['questions_id'] ?>" class="mt-2 d-none">
                                <input type="text" name="q<?= $q['questions_id'] ?>_other" class="form-control" 
                                       placeholder="Please specify..." style="max-width:400px">
                            </div>

                        <?php elseif ($q['type'] == 'text'): ?>
                            <input type="text" name="q<?= $q['questions_id'] ?>" class="form-control mt-1" 
                                   placeholder="Masukkan jawaban" style="max-width:400px" 
                                   data-required="<?= !empty($q['required']) ? 'true' : 'false' ?>"
                                   <?= !empty($q['required']) ? 'required' : '' ?>>

                        <?php elseif ($q['type'] == 'date'): ?>
                            <input type="date" name="q<?= $q['questions_id'] ?>" class="form-control mt-1" 
                                   style="max-width:400px" data-required="<?= !empty($q['required']) ? 'true' : 'false' ?>"
                                   <?= !empty($q['required']) ? 'required' : '' ?>>
                        <?php endif; ?>
                        <div id="error_<?= $q['questions_id'] ?>" class="error-message d-none"></div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary px-4 py-2 mt-4">Submit</button>
            <?php else: ?>
                <div class="alert alert-info">
                    <p class="mb-0 fst-italic">Tidak ada pertanyaan tersedia.</p>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleOther(qId, val) {
            const o = document.getElementById('other_' + qId);
            const i = o.querySelector('input');
            if (val.toLowerCase() === 'other') {
                o.classList.remove('d-none');
                i.required = true;
            } else {
                o.classList.add('d-none');
                i.value = '';
                i.required = false;
            }
        }

        function showError(qId, message) {
            const errorDiv = document.getElementById('error_' + qId);
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('d-none');
            }
        }

        function hideError(qId) {
            const errorDiv = document.getElementById('error_' + qId);
            if (errorDiv) {
                errorDiv.classList.add('d-none');
            }
        }

        function validateForm() {
            let isValid = true;
            
            // Clear all previous error states
            document.querySelectorAll('.error-message').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.invalid-field').forEach(el => el.classList.remove('invalid-field'));

            // Get all form elements that need validation
            const elements = document.querySelectorAll('[data-required="true"]');
            
            elements.forEach(element => {
                let questionId = '';
                let isFieldValid = true;
                let fieldValue = '';
                
                // Extract question ID from name or id
                if (element.name) {
                    questionId = element.name.replace('q', '');
                } else if (element.id) {
                    questionId = element.id.replace('h_', '');
                }

                // Check validation based on element type
                if (element.type === 'radio') {
                    // For radio buttons, check if any in the group is selected
                    const radioGroup = document.querySelectorAll(`input[name="q${questionId}"]`);
                    const isSelected = Array.from(radioGroup).some(radio => radio.checked);
                    if (!isSelected) {
                        showError(questionId, 'This field is required');
                        isValid = false;
                        isFieldValid = false;
                    } else {
                        // Check if "Other" is selected and has value
                        const selectedRadio = Array.from(radioGroup).find(radio => radio.checked);
                        if (selectedRadio && selectedRadio.value.toLowerCase() === 'other') {
                            const otherInput = document.querySelector(`input[name="q${questionId}_other"]`);
                            if (!otherInput || !otherInput.value.trim()) {
                                showError(questionId, 'Please specify the "Other" option');
                                if (otherInput) otherInput.classList.add('invalid-field');
                                isValid = false;
                                isFieldValid = false;
                            }
                        }
                    }
                } else if (element.hasAttribute('data-custom-dropdown')) {
                    // Custom dropdown validation (lokasi_seminar, pemilik_tempat_seminar, dll)
                    fieldValue = element.value;
                    if (!fieldValue || fieldValue.trim() === '') {
                        showError(questionId, 'This field is required');
                        document.getElementById('t_' + questionId).classList.add('invalid-field');
                        isValid = false;
                        isFieldValid = false;
                    } else if (fieldValue.toLowerCase() === 'other') {
                        // Check if "Other" option has value
                        const otherInput = document.querySelector(`input[name="q${questionId}_other"]`);
                        if (!otherInput || !otherInput.value.trim()) {
                            showError(questionId, 'Please specify the "Other" option');
                            if (otherInput) otherInput.classList.add('invalid-field');
                            isValid = false;
                            isFieldValid = false;
                        }
                    }
                } else {
                    // Regular input validation (text, date)
                    fieldValue = element.value;
                    if (!fieldValue || fieldValue.trim() === '') {
                        showError(questionId, 'This field is required');
                        element.classList.add('invalid-field');
                        isValid = false;
                        isFieldValid = false;
                    }
                }

                // Hide error if field is valid
                if (isFieldValid) {
                    hideError(questionId);
                }
            });

            if (!isValid) {
                // Scroll to first error
                const firstError = document.querySelector('.error-message:not(.d-none)');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            return isValid;
        }

        function toggleDropdown(qId) {
            const d = document.getElementById('d_' + qId);
            d.classList.toggle('show');
            if (d.classList.contains('show')) {
                setTimeout(() => {
                    const searchInput = document.getElementById('i_' + qId);
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 100);
            }
        }

        function selectOption(qId, text) {
            // Cek apakah ini adalah opsi "tidak tersedia"
            if (text === 'Tidak ada opsi tersedia') {
                return;
            }
            
            document.getElementById('h_' + qId).value = text;
            document.getElementById('s_' + qId).textContent = text;
            const toggleButton = document.getElementById('t_' + qId);
            toggleButton.classList.add('selected-option');
            toggleButton.classList.remove('invalid-field');
            document.getElementById('d_' + qId).classList.remove('show');
            
            const searchInput = document.getElementById('i_' + qId);
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset semua option items
            document.querySelectorAll('#d_' + qId + ' .option-item').forEach(o => o.style.display = 'block');
            
            // Hide error when user selects an option
            hideError(qId);
            
            // Cek apakah ada "other" option
            toggleOther(qId, text);
        }

        function filterOptions(qId) {
            const f = document.getElementById('i_' + qId).value.toUpperCase();
            const optionItems = document.querySelectorAll('#d_' + qId + ' .option-item');
            
            let hasVisibleOptions = false;
            optionItems.forEach(o => {
                // Jangan filter opsi "tidak tersedia"
                if (o.textContent.trim() === 'Tidak ada opsi tersedia') {
                    // Tampilkan "tidak tersedia" hanya jika tidak ada opsi lain yang cocok
                    return;
                } else if (o.textContent.toUpperCase().indexOf(f) > -1) {
                    o.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    o.style.display = 'none';
                }
            });
            
            // Tampilkan pesan "tidak tersedia" hanya jika tidak ada opsi yang cocok dengan pencarian
            const noOptionsItem = document.querySelector('#d_' + qId + ' .option-item.text-muted');
            if (noOptionsItem) {
                if (!hasVisibleOptions && f.length > 0) {
                    noOptionsItem.style.display = 'block';
                } else if (optionItems.length <= 1) {
                    // Jika memang tidak ada opsi sama sekali (hanya ada pesan "tidak tersedia")
                    noOptionsItem.style.display = 'block';
                } else {
                    noOptionsItem.style.display = 'none';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('click', e => {
                if (!e.target.closest('.custom-dropdown')) {
                    document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
                }
            });

            // Add event listeners to clear errors when user starts typing/selecting
            document.querySelectorAll('input, select').forEach(element => {
                element.addEventListener('input', function() {
                    if (this.classList.contains('invalid-field')) {
                        this.classList.remove('invalid-field');
                    }
                });
                
                element.addEventListener('change', function() {
                    if (this.classList.contains('invalid-field')) {
                        this.classList.remove('invalid-field');
                    }
                });
            });
        });
    </script>
</body>
</html>