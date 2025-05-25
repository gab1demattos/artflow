// Handles the Edit Service modal logic: open, pre-fill, and submit

document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('edit-service-btn');
    const modalOverlay = document.getElementById('edit-service-modal-overlay');
    const modal = document.getElementById('editServiceModal');
    const closeBtn = document.getElementById('close-edit-service-modal');
    const cancelBtn = document.getElementById('cancel-edit-service');
    const form = document.getElementById('editServiceForm');

    // Open modal
    if (editBtn && modalOverlay) {
        editBtn.addEventListener('click', function () {
            // Get current service data from data attributes or DOM
            const serviceId = editBtn.getAttribute('data-service-id');
            const title = editBtn.getAttribute('data-title');
            const description = editBtn.getAttribute('data-description');
            const category = editBtn.getAttribute('data-category');
            const subcategories = editBtn.getAttribute('data-subcategories') || editBtn.getAttribute('data-subcategory');
            const price = editBtn.getAttribute('data-price');
            const delivery = editBtn.getAttribute('data-delivery');

            document.getElementById('edit-service-id').value = serviceId;
            document.getElementById('edit-service-title').value = title;
            document.getElementById('edit-service-description').value = description;
            document.getElementById('edit-service-category').value = category;
            document.getElementById('edit-service-price').value = price;
            document.getElementById('edit-service-delivery').value = delivery;

            // Set up subcategory state for overlay logic
            currentCategoryId = category;
            // Parse subcategories as array of strings
            let subcatArr = [];
            if (subcategories) {
                if (Array.isArray(subcategories)) {
                    subcatArr = subcategories;
                } else if (typeof subcategories === 'string' && subcategories.includes(',')) {
                    subcatArr = subcategories.split(',').map(s => s.trim()).filter(Boolean);
                } else {
                    subcatArr = [subcategories];
                }
            }
            selectedSubcatValues = subcatArr;

            // Show summary
            if (subcategorySection) {
                if (selectedSubcatValues.length > 0 && currentCategoryId && editSubcategoriesByCategory[currentCategoryId]) {
                    const summary = document.createElement('div');
                    summary.className = 'subcategory-summary';
                    summary.textContent = 'Selected: ' + editSubcategoriesByCategory[currentCategoryId].filter(subcat => selectedSubcatValues.includes(subcat.id)).map(subcat => subcat.name).join(', ');
                    subcategorySection.innerHTML = '';
                    subcategorySection.appendChild(summary);
                } else {
                    subcategorySection.innerHTML = '';
                }
            }
            // Remove old hidden inputs
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
            // Add new hidden inputs
            selectedSubcatValues.forEach(val => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'subcategories[]';
                hidden.value = val;
                form.appendChild(hidden);
            });
            updateSubcatBtnVisibility();

            // Show modal (fix: always remove 'hidden' from overlay, not modal)
            modalOverlay.classList.remove('hidden');
            document.body.classList.add('modal-open');
        });
    }

    // Cancel button closes modal
    if (cancelBtn && modalOverlay) {
        cancelBtn.addEventListener('click', function () {
            modalOverlay.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });
    }

    // Close (X) button closes modal
    if (closeBtn && modalOverlay) {
        closeBtn.addEventListener('click', function () {
            modalOverlay.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });
    }

    // Clicking outside modal closes it
    if (modalOverlay && modal) {
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
        // Prevent closing when clicking inside modal
        modal.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // (Optional) Reset form on close
    function resetForm() {
        if (form) form.reset();
        const preview = document.getElementById('edit-service-image-preview');
        if (preview) preview.innerHTML = '';
    }
    [cancelBtn, closeBtn].forEach(btn => {
        if (btn) btn.addEventListener('click', resetForm);
    });
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) resetForm();
        });
    }

    // Submit form
    form.addEventListener('submit', function (e) {
        // Allow default form submission (POST to PHP)
        // Optionally, you can show a loading spinner or disable the button here
    });

    // --- Subcategory selection logic (like new service modal) ---
    const openSubcatBtn = document.getElementById('edit-open-subcategory-overlay');
    const categorySelect = document.getElementById('edit-service-category');
    const subcategorySection = document.getElementById('edit-subcategory-section');
    const subcategoryOverlay = document.getElementById('subcategory-overlay');
    const subcategoryModal = document.getElementById('subcategory-modal');
    const subcategoryForm = document.getElementById('subcategory-form');
    const subcategoryCheckboxesDiv = subcategoryModal ? subcategoryModal.querySelector('.subcategory-checkboxes') : null;
    const closeSubcategoryBtns = document.querySelectorAll('.close-subcategory-modal');
    let selectedSubcatValues = [];
    let currentCategoryId = null;

    // Show/hide subcategory button based on category
    function updateSubcatBtnVisibility() {
        const catId = categorySelect.value;
        currentCategoryId = catId;
        if (catId && editSubcategoriesByCategory[catId] && editSubcategoriesByCategory[catId].length > 0) {
            openSubcatBtn.style.display = '';
        } else {
            openSubcatBtn.style.display = 'none';
            subcategorySection.innerHTML = '';
            selectedSubcatValues = [];
            // Remove hidden inputs
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
        }
    }
    if (categorySelect && openSubcatBtn) {
        categorySelect.addEventListener('change', function () {
            updateSubcatBtnVisibility();
        });
    }

    // Open subcategory overlay
    if (openSubcatBtn && subcategoryOverlay && subcategoryCheckboxesDiv) {
        openSubcatBtn.addEventListener('click', function () {
            if (!currentCategoryId || !editSubcategoriesByCategory[currentCategoryId]) return;
            subcategoryCheckboxesDiv.innerHTML = '';
            editSubcategoriesByCategory[currentCategoryId].forEach(subcat => {
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'subcategories[]';
                checkbox.value = subcat.id;
                checkbox.id = 'edit-subcat-' + subcat.id;
                if (selectedSubcatValues.includes(subcat.id)) checkbox.checked = true;
                const cbLabel = document.createElement('label');
                cbLabel.htmlFor = checkbox.id;
                cbLabel.textContent = subcat.name;
                subcategoryCheckboxesDiv.appendChild(checkbox);
                subcategoryCheckboxesDiv.appendChild(cbLabel);
                if (checkbox.checked) cbLabel.classList.add('selected');
                checkbox.addEventListener('change', function () {
                    if (checkbox.checked) {
                        cbLabel.classList.add('selected');
                    } else {
                        cbLabel.classList.remove('selected');
                    }
                });
            });
            subcategoryOverlay.classList.remove('hidden');
        });
    }

    // Handle subcategory form submit (confirm selection)
    if (subcategoryForm) {
        subcategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const checked = Array.from(subcategoryCheckboxesDiv.querySelectorAll('input[type="checkbox"]:checked'));
            selectedSubcatValues = checked.map(cb => cb.value);
            // Show summary
            if (subcategorySection) {
                if (selectedSubcatValues.length > 0) {
                    const summary = document.createElement('div');
                    summary.className = 'subcategory-summary';
                    summary.textContent = 'Selected: ' + checked.map(cb => {
                        const label = subcategoryCheckboxesDiv.querySelector('label[for="' + cb.id + '"]');
                        return label ? label.textContent.trim() : '';
                    }).filter(Boolean).join(', ');
                    subcategorySection.innerHTML = '';
                    subcategorySection.appendChild(summary);
                } else {
                    subcategorySection.innerHTML = '';
                }
            }
            // Remove old hidden inputs
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
            // Add new hidden inputs
            selectedSubcatValues.forEach(val => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'subcategories[]';
                hidden.value = val;
                form.appendChild(hidden);
            });
            if (subcategoryOverlay) subcategoryOverlay.classList.add('hidden');
        });
    }
    // Close/cancel subcategory overlay
    closeSubcategoryBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (subcategoryOverlay) subcategoryOverlay.classList.add('hidden');
        });
    });

    // When opening the edit modal, prefill subcategories
    if (editBtn && modalOverlay) {
        editBtn.addEventListener('click', function () {
            // Get subcategories (single or multiple, as array of strings)
            let subcategories = editBtn.getAttribute('data-subcategories');
            if (!subcategories) subcategories = editBtn.getAttribute('data-subcategory');
            if (subcategories) {
                if (Array.isArray(subcategories)) {
                    selectedSubcatValues = subcategories;
                } else if (typeof subcategories === 'string' && subcategories.includes(',')) {
                    selectedSubcatValues = subcategories.split(',').map(s => s.trim()).filter(Boolean);
                } else {
                    selectedSubcatValues = [subcategories];
                }
            } else {
                selectedSubcatValues = [];
            }
            // Show summary
            if (subcategorySection) {
                if (selectedSubcatValues.length > 0 && currentCategoryId && editSubcategoriesByCategory[currentCategoryId]) {
                    const summary = document.createElement('div');
                    summary.className = 'subcategory-summary';
                    summary.textContent = 'Selected: ' + editSubcategoriesByCategory[currentCategoryId].filter(subcat => selectedSubcatValues.includes(subcat.id)).map(subcat => subcat.name).join(', ');
                    subcategorySection.innerHTML = '';
                    subcategorySection.appendChild(summary);
                } else {
                    subcategorySection.innerHTML = '';
                }
            }
            // Remove old hidden inputs
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
            // Add new hidden inputs
            selectedSubcatValues.forEach(val => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'subcategories[]';
                hidden.value = val;
                form.appendChild(hidden);
            });
            updateSubcatBtnVisibility();
        });
    }
});
