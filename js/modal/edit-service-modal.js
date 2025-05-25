document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('edit-service-btn');
    const modalOverlay = document.getElementById('edit-service-modal-overlay');
    const modal = document.getElementById('editServiceModal');
    const closeBtn = document.getElementById('close-edit-service-modal');
    const cancelBtn = document.getElementById('cancel-edit-service');
    const form = document.getElementById('editServiceForm');

    if (editBtn && modalOverlay) {
        editBtn.addEventListener('click', function () {
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

            currentCategoryId = category;
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
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
            selectedSubcatValues.forEach(val => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'subcategories[]';
                hidden.value = val;
                form.appendChild(hidden);
            });
            updateSubcatBtnVisibility();

            modalOverlay.classList.remove('hidden');
            document.body.classList.add('modal-open');
        });
    }

    if (cancelBtn && modalOverlay) {
        cancelBtn.addEventListener('click', function () {
            modalOverlay.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });
    }

    if (closeBtn && modalOverlay) {
        closeBtn.addEventListener('click', function () {
            modalOverlay.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });
    }

    if (modalOverlay && modal) {
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
        modal.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

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

    form.addEventListener('submit', function (e) {
    });

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

    function updateSubcatBtnVisibility() {
        const catId = categorySelect.value;
        currentCategoryId = catId;
        if (catId && editSubcategoriesByCategory[catId] && editSubcategoriesByCategory[catId].length > 0) {
            openSubcatBtn.style.display = '';
        } else {
            openSubcatBtn.style.display = 'none';
            subcategorySection.innerHTML = '';
            selectedSubcatValues = [];
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
        }
    }
    if (categorySelect && openSubcatBtn) {
        categorySelect.addEventListener('change', function () {
            updateSubcatBtnVisibility();
        });
    }

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

    if (subcategoryForm) {
        subcategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const checked = Array.from(subcategoryCheckboxesDiv.querySelectorAll('input[type="checkbox"]:checked'));
            selectedSubcatValues = checked.map(cb => cb.value);
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
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
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
    closeSubcategoryBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (subcategoryOverlay) subcategoryOverlay.classList.add('hidden');
        });
    });

    if (editBtn && modalOverlay) {
        editBtn.addEventListener('click', function () {
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
            Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
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
