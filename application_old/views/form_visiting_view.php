<!DOCTYPE html>
<html>
<head>
    <title>Visiting <?php echo $visiting_type; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .jenis-kasus-hidden { display: none !important; }
        .form-label[required]::after {
            content: " *";
            color: red;
        }
        .currency-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
            z-index: 3;
        }
        .currency-input { padding-left: 35px !important; }
        
        /* Searchable dropdown styles */
        .custom-dropdown { position: relative; }
        .dropdown-toggle {
            background-color: white; 
            color: #333; 
            border: 1px solid #dee2e6; 
            cursor: pointer;
            text-align: left; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            width: 100%;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
        }
        .dropdown-toggle:hover, .dropdown-toggle:focus { 
            background-color: #f8f9fa; 
            border-color: #0d6efd; 
        }
        .dropdown-toggle:disabled, .dropdown-toggle.disabled { 
            background-color: #e9ecef; 
            color: #6c757d; 
            cursor: not-allowed; 
            border-color: #dee2e6; 
        }
        .dropdown-toggle::after { 
            content: "▼"; 
            font-size: 12px; 
        }
        .dropdown-toggle:invalid, .dropdown-toggle[data-invalid="true"] {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        .farm-search-input { 
            border: none; 
            border-bottom: 1px solid #dee2e6; 
            background-color: #f8f9fa; 
            width: 100%;
            padding: 8px 12px;
        }
        .farm-search-input:focus { 
            outline: 2px solid #0d6efd; 
            background-color: white; 
        }
        .dropdown-content {
            display: none; 
            position: absolute; 
            background-color: white; 
            min-width: 100%;
            max-height: 200px; 
            overflow-y: auto; 
            border: 1px solid #dee2e6;
            border-radius: 0 0 0.375rem 0.375rem; 
            border-top: none; 
            z-index: 1000;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .dropdown-content .farm-option {
            color: #333; 
            padding: 10px 12px; 
            text-decoration: none;
            display: block; 
            cursor: pointer; 
            border-bottom: 1px solid #eee;
        }
        .dropdown-content .farm-option:hover { 
            background-color: #f8f9fa; 
        }
        .dropdown-content .farm-option:last-child { 
            border-bottom: none; 
        }
        .show { 
            display: block; 
        }
        .no-options {
            padding: 10px 12px;
            color: #6c757d;
            font-style: italic;
            text-align: center;
        }
        .invalid-field{border-color:#dc3545 !important;}
        .error-message{color:#dc3545;font-size:0.875em;margin-top:0.25rem;}
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-lg-8">
                <h2 class="mb-4 text-dark fw-bold">Visiting <?php echo $visiting_type; ?> - <?php echo $nama_lokasi_header; ?></h2>
                <form method="post" action="" id="visitingForm" class="needs-validation" novalidate>
                    <input type="hidden" name="action" value="<?php echo $action_type; ?>">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="location_address" id="location_address">
                    
                    <div id="questions">
                        <?php if (!empty($questions)): ?>
                            <?php foreach ($questions as $q): ?>
                                <?php 
                                $isJenisKasus = (isset($q['field_name']) && $q['field_name'] == 'jenis_kasus') ||
                                               (isset($q['question_text']) && stripos($q['question_text'], 'jenis kasus') !== false);
                                
                                $isJenisKasusDropdown = false;
                                if (isset($q['question_text'])) {
                                    $questionText = strtolower($q['question_text']);
                                    $dropdownTypes = ['bacterial', 'virus', 'parasit', 'jamur', 'lain-lain', 'lain_lain'];
                                    $isJenisKasusDropdown = array_filter($dropdownTypes, function($type) use ($questionText) {
                                        return strpos($questionText, $type) !== false;
                                    });
                                }
                                
                                if (isset($q['field_name']) && $q['field_name'] == 'kunjungan_ke') {
                                    continue;
                                }
                                
                                $isCurrencyField = (isset($q['field_name']) && $q['field_name'] == 'harga_live_bird') ||
                                                 (isset($q['question_text']) && stripos($q['question_text'], 'harga live bird') !== false);
                                
                                $hideClass = ($isJenisKasus || $isJenisKasusDropdown) ? 'jenis-kasus-hidden' : '';
                                ?>
                                <div class="mb-4 <?= $hideClass ?>">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        <?= $q['question_text'] ?>
                                        <?php if (!empty($q['required'])): ?> 
                                            <span class="text-danger">*</span> 
                                        <?php endif; ?>
                                    </label>
                                    
                                    <?php if ($q['type'] == 'radio' && !empty($q['options'])): ?>
                                        <div class="d-flex flex-column gap-2">
                                            <?php foreach ($q['options'] as $opt): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="q<?= $q['questions_id'] ?>" 
                                                           id="q<?= $q['questions_id'] ?>_<?= $opt['option_text'] ?>"
                                                           value="<?= $opt['option_text'] ?>" 
                                                           <?= !empty($q['required']) ? 'required' : '' ?>
                                                           <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>
                                                           <?php if ($q['field_name'] == 'tujuan_kunjungan'): ?>onchange="toggleJenisKasus(this.value)"<?php endif; ?>
                                                           <?php if ($q['field_name'] == 'jenis_kasus'): ?>onchange="toggleJenisKasusFields(this.value)"<?php endif; ?>>
                                                    <label class="form-check-label" for="q<?= $q['questions_id'] ?>_<?= $opt['option_text'] ?>">
                                                        <?= $opt['option_text'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                    <?php elseif ($q['type'] == 'text'): ?>
                                        <?php if ($isCurrencyField): ?>
                                            <div class="position-relative" style="max-width: 400px;">
                                                <span class="currency-prefix">Rp</span>
                                                <input type="text" 
                                                       class="form-control currency-field currency-input" 
                                                       name="q<?= $q['questions_id'] ?>" 
                                                       placeholder="0"
                                                       data-currency="true"
                                                       <?= !empty($q['required']) ? 'required' : '' ?>
                                                       <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                            </div>
                                        <?php else: ?>
                                            <input type="text" 
                                                   class="form-control" 
                                                   style="max-width: 400px;"
                                                   name="q<?= $q['questions_id'] ?>" 
                                                   placeholder="Masukkan jawaban Anda"
                                                   <?= !empty($q['required']) ? 'required' : '' ?>
                                                   <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                        <?php endif; ?>
                                           
                                    <?php elseif ($q['type'] == 'textarea'): ?>
                                        <textarea class="form-control auto-resize-textarea" 
                                                  style="max-width: 400px; min-height: 80px; resize: none;"
                                                  name="q<?= $q['questions_id'] ?>" 
                                                  placeholder="Masukkan jawaban Anda"
                                                  <?= !empty($q['required']) ? 'required' : '' ?>
                                                  <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>></textarea>
                                              
                                    <?php elseif ($q['type'] == 'select'): ?>
                                        <?php if ($isJenisKasusDropdown): ?>
                                            <select class="form-control" 
                                                    style="max-width: 400px;"
                                                    name="q<?= $q['questions_id'] ?>" 
                                                    <?= !empty($q['required']) ? 'required' : '' ?>
                                                    <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                                <option value="">-- Pilih Jawaban --</option>
                                                <?php if (!empty($q['options'])): ?>
                                                    <?php foreach ($q['options'] as $opt): ?>
                                                        <option value="<?= htmlspecialchars($opt['option_text'], ENT_QUOTES) ?>">
                                                            <?= htmlspecialchars($opt['option_text']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        <?php else: ?>
                                            <div class="custom-dropdown" style="max-width: 400px;">
                                                <input type="hidden" 
                                                       name="q<?= $q['questions_id'] ?>" 
                                                       id="dd_hidden_<?= $q['questions_id'] ?>" 
                                                       <?= !empty($q['required']) ? 'required' : '' ?>
                                                       <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                                <button type="button" 
                                                        class="btn dropdown-toggle" 
                                                        id="dd_btn_<?= $q['questions_id'] ?>" 
                                                        onclick="toggleDropdown(<?= $q['questions_id'] ?>)"
                                                        <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                                    <span>-- Pilih Jawaban --</span>
                                                </button>
                                                <div id="dd_content_<?= $q['questions_id'] ?>" class="dropdown-content">
                                                    <input type="text" 
                                                           placeholder="Cari..." 
                                                           id="dd_search_<?= $q['questions_id'] ?>" 
                                                           class="farm-search-input" 
                                                           onkeyup="filterOptions(<?= $q['questions_id'] ?>)">
                                                    <?php if (!empty($q['options'])): ?>
                                                        <?php foreach ($q['options'] as $opt): ?>
                                                            <div class="farm-option" onclick="selectOption(<?= $q['questions_id'] ?>, '<?= htmlspecialchars($opt['option_text'], ENT_QUOTES) ?>')">
                                                                <?= htmlspecialchars($opt['option_text']) ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="no-options">Tidak ada pilihan tersedia</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                    <?php elseif ($q['type'] == 'date'): ?>
                                        <input type="date" 
                                               class="form-control" 
                                               style="max-width: 400px;"
                                               name="q<?= $q['questions_id'] ?>" 
                                               <?= !empty($q['required']) ? 'required' : '' ?>
                                               <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                           
                                    <?php elseif ($q['type'] == 'checkbox' && !empty($q['options'])): ?>
                                        <div class="d-flex flex-column gap-2">
                                            <?php foreach ($q['options'] as $opt): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="q<?= $q['questions_id'] ?>[]" 
                                                           id="q<?= $q['questions_id'] ?>_<?= $opt['option_text'] ?>"
                                                           value="<?= $opt['option_text'] ?>"
                                                           <?= ($isJenisKasus || $isJenisKasusDropdown) ? 'disabled' : '' ?>>
                                                    <label class="form-check-label" for="q<?= $q['questions_id'] ?>_<?= $opt['option_text'] ?>">
                                                        <?= $opt['option_text'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div id="error_<?= $q['questions_id'] ?>" class="error-message d-none"></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">Tidak ada pertanyaan untuk form visiting <?php echo $visiting_type; ?>.</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-block mt-4">
                        <button type="submit" class="btn btn-primary px-10">
                            <?php echo ($action_type === 'next') ? 'Next' : 'Submit'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const CurrencyUtils = {
            formatCurrency(value) {
                const numericValue = value.replace(/[^\d]/g, '');
                return numericValue === '' ? '' : numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            },
            removeCurrencyFormat(value) {
                return value.replace(/,/g, '');
            },
            validateNumericInput(event) {
                if ([8, 9, 27, 13, 46].indexOf(event.keyCode) !== -1 ||
                    (event.keyCode === 65 && event.ctrlKey) ||
                    (event.keyCode === 67 && event.ctrlKey) ||
                    (event.keyCode === 86 && event.ctrlKey) ||
                    (event.keyCode === 88 && event.ctrlKey)) {
                    return;
                }
                if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        };

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

        // Searchable dropdown functions
        function toggleDropdown(qId) {
            const dropdown = document.getElementById(`dd_content_${qId}`);
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        function selectOption(qId, text) {
            const hiddenInput = document.getElementById(`dd_hidden_${qId}`);
            const button = document.getElementById(`dd_btn_${qId}`);
            const dropdown = document.getElementById(`dd_content_${qId}`);
            
            if (hiddenInput) hiddenInput.value = text;
            if (button) {
                const span = button.querySelector('span');
                if (span) span.textContent = text;
                
                // Remove invalid attribute when option is selected
                button.removeAttribute('data-invalid');
                button.classList.remove('invalid-field');
            }
            if (dropdown) dropdown.classList.remove('show');
            
            // Clear custom validity if option is selected
            if (hiddenInput && text && text !== '-- Pilih Jawaban --') {
                hiddenInput.setCustomValidity('');
            }
            
            // Hide error when user selects an option
            hideError(qId);
        }

        function filterOptions(qId) {
            const searchInput = document.getElementById(`dd_search_${qId}`);
            const dropdown = document.getElementById(`dd_content_${qId}`);
            
            if (!searchInput || !dropdown) return;
            
            const filter = searchInput.value.toUpperCase();
            const options = dropdown.querySelectorAll('.farm-option');
            
            let hasVisibleOptions = false;
            options.forEach(option => {
                const text = option.textContent || option.innerText;
                if (text.toUpperCase().includes(filter)) {
                    option.style.display = '';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Show/hide no options message
            let noOptionsDiv = dropdown.querySelector('.no-options');
            if (!hasVisibleOptions && filter) {
                if (!noOptionsDiv) {
                    noOptionsDiv = document.createElement('div');
                    noOptionsDiv.className = 'no-options';
                    noOptionsDiv.textContent = 'Tidak ada pilihan yang cocok';
                    dropdown.appendChild(noOptionsDiv);
                }
                noOptionsDiv.style.display = 'block';
            } else if (noOptionsDiv) {
                noOptionsDiv.style.display = 'none';
            }
        }

        function initializeCurrencyInputs() {
            document.querySelectorAll('.currency-field').forEach(input => {
                input.addEventListener('input', function(e) {
                    const cursorPosition = e.target.selectionStart;
                    const oldValue = e.target.value;
                    const newValue = CurrencyUtils.formatCurrency(oldValue);
                    
                    if (newValue !== oldValue) {
                        e.target.value = newValue;
                        const newCursorPosition = cursorPosition + (newValue.length - oldValue.length);
                        e.target.setSelectionRange(newCursorPosition, newCursorPosition);
                    }
                });
                
                input.addEventListener('keydown', CurrencyUtils.validateNumericInput);
                input.addEventListener('blur', e => e.target.value = CurrencyUtils.formatCurrency(e.target.value));
            });
        }

        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeCurrencyInputs();
            
            // Auto-resize textareas
            document.querySelectorAll('.auto-resize-textarea').forEach(textarea => {
                autoResizeTextarea(textarea);
                textarea.addEventListener('input', () => autoResizeTextarea(textarea));
                textarea.addEventListener('paste', () => setTimeout(() => autoResizeTextarea(textarea), 10));
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown')) {
                    document.querySelectorAll('.dropdown-content.show').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
            });

            // Add event listeners to clear errors when user starts typing/selecting
            document.querySelectorAll('input, select, textarea').forEach(element => {
                element.addEventListener('input', function() {
                    if (this.classList.contains('invalid-field')) {
                        this.classList.remove('invalid-field');
                        
                        // Extract question ID and hide error
                        let questionId = '';
                        if (this.name) {
                            questionId = this.name.replace('q', '').replace('[]', '');
                        } else if (this.id) {
                            questionId = this.id.replace('dd_hidden_', '');
                        }
                        if (questionId) {
                            hideError(questionId);
                        }
                    }
                });
                
                element.addEventListener('change', function() {
                    if (this.classList.contains('invalid-field')) {
                        this.classList.remove('invalid-field');
                        
                        // Extract question ID and hide error
                        let questionId = '';
                        if (this.name) {
                            questionId = this.name.replace('q', '').replace('[]', '');
                        } else if (this.id) {
                            questionId = this.id.replace('dd_hidden_', '');
                        }
                        if (questionId) {
                            hideError(questionId);
                        }
                    }
                });
            });
        });

        // Custom validation for dropdown fields before form submission
        function validateCustomDropdowns() {
            let isValid = true;
            
            // Clear all previous error states
            document.querySelectorAll('.error-message').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.invalid-field').forEach(el => el.classList.remove('invalid-field'));
            
            // Only validate dropdowns that are currently visible and enabled
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                // Skip if the dropdown's parent container is hidden
                if (dropdown.closest('.jenis-kasus-hidden')) {
                    return;
                }
                
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                const button = dropdown.querySelector('.dropdown-toggle');
                const isRequired = hiddenInput && hiddenInput.hasAttribute('required');
                const isDisabled = hiddenInput && hiddenInput.disabled;
                
                // Only validate if field is required, not disabled, and visible
                if (isRequired && !isDisabled) {
                    let questionId = '';
                    if (hiddenInput.name) {
                        questionId = hiddenInput.name.replace('q', '');
                    } else if (hiddenInput.id) {
                        questionId = hiddenInput.id.replace('dd_hidden_', '');
                    }
                    
                    if (!hiddenInput.value || hiddenInput.value.trim() === '' || hiddenInput.value === '-- Pilih Jawaban --') {
                        hiddenInput.setCustomValidity('Please select an option.');
                        showError(questionId, 'This field is required');
                        
                        // Add invalid attribute to button for CSS styling
                        if (button) {
                            button.setAttribute('data-invalid', 'true');
                            button.classList.add('invalid-field');
                        }
                        
                        isValid = false;
                    } else {
                        hiddenInput.setCustomValidity('');
                        hideError(questionId);
                        
                        // Remove invalid attribute from button
                        if (button) {
                            button.removeAttribute('data-invalid');
                            button.classList.remove('invalid-field');
                        }
                    }
                } else if (hiddenInput && button) {
                    // Clear validation for non-required or disabled fields
                    hiddenInput.setCustomValidity('');
                    button.removeAttribute('data-invalid');
                    button.classList.remove('invalid-field');
                }
            });

            // Also validate other form fields
            const elements = document.querySelectorAll('input[required], select[required], textarea[required]');
            
            elements.forEach(element => {
                // Skip if element is in a hidden container
                if (element.closest('.jenis-kasus-hidden')) {
                    return;
                }
                
                let questionId = '';
                let isFieldValid = true;
                let fieldValue = '';
                
                // Extract question ID from name or id
                if (element.name) {
                    questionId = element.name.replace('q', '').replace('[]', '');
                } else if (element.id) {
                    questionId = element.id.replace('dd_hidden_', '');
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
                    }
                } else if (element.type === 'checkbox') {
                    // For checkbox groups, check if at least one is selected
                    const checkboxGroup = document.querySelectorAll(`input[name="q${questionId}[]"]`);
                    const isSelected = Array.from(checkboxGroup).some(checkbox => checkbox.checked);
                    if (!isSelected) {
                        showError(questionId, 'This field is required');
                        isValid = false;
                        isFieldValid = false;
                    }
                } else {
                    // Regular input validation (text, date, select, textarea)
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
                    element.classList.remove('invalid-field');
                }
            });

            // Clear validation messages for all select dropdowns to avoid blocking submission
            document.querySelectorAll('select').forEach(select => {
                select.setCustomValidity('');
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

        document.getElementById('visitingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate all form fields including custom dropdowns
            const allFieldsValid = validateCustomDropdowns();
            
            // If validation fails, stop submission
            if (!allFieldsValid) {
                return;
            }
            
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            
            try {
                // Get location
                const position = await getCurrentLocation();
                const { latitude, longitude } = position.coords;
                
                // Format coordinates
                const formattedLat = parseFloat(latitude).toFixed(7);
                const formattedLon = parseFloat(longitude).toFixed(7);
                
                // Get address using Nominatim
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?` +
                        `format=json&lat=${formattedLat}&lon=${formattedLon}` +
                        `&zoom=18&addressdetails=1&accept-language=id`,
                        {
                            method: 'GET',
                            headers: {
                                'User-Agent': 'Mozilla/5.0',
                                'Accept': 'application/json',
                                'Accept-Language': 'id'
                            },
                            referrerPolicy: 'no-referrer'
                        }
                    );

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (!data.display_name) {
                        throw new Error('No address found');
                    }

                    // Set form values
                    document.getElementById('latitude').value = formattedLat;
                    document.getElementById('longitude').value = formattedLon;
                    document.getElementById('location_address').value = data.display_name;
                    
                    // Process currency fields
                    document.querySelectorAll('.currency-field').forEach(input => {
                        if (input.value) {
                            input.value = CurrencyUtils.removeCurrencyFormat(input.value);
                        }
                    });
                    
                    // Submit form
                    this.submit();
                    
                } catch (error) {
                    console.error('Address fetch error:', error);
                    throw new Error(`Failed to get address: ${error.message}`);
                }
                
            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
                submitBtn.disabled = false;
            }
        });

        function getCurrentLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation is not supported by your browser'));
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(resolve,
                    (error) => {
                        let message = 'Location error: ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                message += 'Please enable location access';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message += 'Location information unavailable';
                                break;
                            case error.TIMEOUT:
                                message += 'Location request timed out';
                                break;
                            default:
                                message += 'Unknown error occurred';
                        }
                        reject(new Error(message));
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        const JENIS_KASUS_MAPPING = {
            'Bacterial': 'bacterial', 'Viral': 'virus', 'Parasit': 'parasit',
            'Jamur': 'jamur', 'Lain-lain': 'lain_lain', 'Lambat puncak': null
        };

        const FormUtils = {
            isKasusSelected() {
                return Array.from(document.querySelectorAll('input[type="radio"]:checked')).some(radio => 
                    radio.closest('.mb-4').querySelector('label').textContent.toLowerCase().includes('tujuan kunjungan') &&
                    radio.value === 'Kasus'
                );
            },
            hideFormGroup(group) {
                group.classList.add('jenis-kasus-hidden');
                group.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = true;
                    input.removeAttribute('required');
                    if (input.type === 'radio' || input.type === 'checkbox') {
                        input.checked = false;
                    } else {
                        input.value = '';
                    }
                    input.setCustomValidity(''); // Clear any validation messages
                });
                
                // Also disable custom dropdown buttons
                const customDropdowns = group.querySelectorAll('.custom-dropdown');
                customDropdowns.forEach(dropdown => {
                    const button = dropdown.querySelector('.dropdown-toggle');
                    const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                    if (button) {
                        button.disabled = true;
                        button.classList.add('disabled');
                    }
                    if (hiddenInput) {
                        hiddenInput.disabled = true;
                        hiddenInput.removeAttribute('required');
                        hiddenInput.value = '';
                        hiddenInput.setCustomValidity(''); // Clear any validation messages
                    }
                    // Reset button text
                    const span = button?.querySelector('span');
                    if (span) span.textContent = '-- Pilih Jawaban --';
                });
            },
            showFormGroup(group) {
                group.classList.remove('jenis-kasus-hidden');
                group.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = false;
                    const label = group.querySelector('label');
                    if (label && label.innerHTML.includes('<span class="text-danger">*</span>')) {
                        input.setAttribute('required', 'required');
                    }
                    input.setCustomValidity(''); // Clear any validation messages
                });
                
                // Also enable custom dropdown buttons
                const customDropdowns = group.querySelectorAll('.custom-dropdown');
                customDropdowns.forEach(dropdown => {
                    const button = dropdown.querySelector('.dropdown-toggle');
                    const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                    if (button) {
                        button.disabled = false;
                        button.classList.remove('disabled');
                    }
                    if (hiddenInput) {
                        hiddenInput.disabled = false;
                        const label = group.querySelector('label');
                        if (label && label.innerHTML.includes('<span class="text-danger">*</span>')) {
                            hiddenInput.setAttribute('required', 'required');
                        }
                        hiddenInput.setCustomValidity(''); // Clear any validation messages
                    }
                });
            },
            findFormGroupByLabel(labelText) {
                return Array.from(document.querySelectorAll('.mb-4')).find(group => {
                    const label = group.querySelector('label');
                    return label && label.textContent.toLowerCase().includes(labelText.toLowerCase());
                });
            },
            hideAllJenisKasusFields() {
                const fieldTypes = ['bacterial', 'virus', 'parasit', 'jamur', 'lain-lain', 'lain_lain'];
                document.querySelectorAll('.mb-4').forEach(group => {
                    const label = group.querySelector('label');
                    if (label && fieldTypes.some(type => label.textContent.toLowerCase().includes(type))) {
                        this.hideFormGroup(group);
                    }
                });
            }
        };

        function toggleJenisKasus(tujuanKunjungan) {
            const jenisKasusGroup = FormUtils.findFormGroupByLabel('jenis kasus');
            if (!jenisKasusGroup) return;
            
            if (tujuanKunjungan === 'Monitoring') {
                FormUtils.hideFormGroup(jenisKasusGroup);
                FormUtils.hideAllJenisKasusFields();
            } else if (tujuanKunjungan === 'Kasus') {
                FormUtils.showFormGroup(jenisKasusGroup);
                FormUtils.hideAllJenisKasusFields();
            }
        }

        function toggleJenisKasusFields(jenisKasus) {
            const targetFieldName = JENIS_KASUS_MAPPING[jenisKasus];
            
            // Always hide all jenis kasus fields first
            FormUtils.hideAllJenisKasusFields();
            
            if (targetFieldName === null || targetFieldName === undefined) {
                // For "Lambat puncak" or unmapped options, keep all fields hidden
                return;
            }
            
            // Show only the matching field
            document.querySelectorAll('.mb-4').forEach(group => {
                const label = group.querySelector('label');
                if (label) {
                    const labelText = label.textContent.toLowerCase();
                    if (labelText.includes(targetFieldName.toLowerCase()) || 
                        (targetFieldName === 'virus' && labelText.includes('virus')) ||
                        (targetFieldName === 'lain_lain' && (labelText.includes('lain-lain') || labelText.includes('lain_lain')))) {
                        FormUtils.showFormGroup(group);
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (!FormUtils.isKasusSelected()) {
                const jenisKasusGroup = FormUtils.findFormGroupByLabel('jenis kasus');
                if (jenisKasusGroup) FormUtils.hideFormGroup(jenisKasusGroup);
                FormUtils.hideAllJenisKasusFields();
            }
        });
        </script>
        </body>
        </html>