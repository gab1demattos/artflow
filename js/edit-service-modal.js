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
            const subcategory = editBtn.getAttribute('data-subcategory');
            const price = editBtn.getAttribute('data-price');
            const delivery = editBtn.getAttribute('data-delivery');

            document.getElementById('edit-service-id').value = serviceId;
            document.getElementById('edit-service-title').value = title;
            document.getElementById('edit-service-description').value = description;
            document.getElementById('edit-service-category').value = category;
            // Populate subcategories
            const subcatSelect = document.getElementById('edit-service-subcategory');
            subcatSelect.innerHTML = '';
            if (editSubcategoriesByCategory[category]) {
                editSubcategoriesByCategory[category].forEach(function (subcat) {
                    const opt = document.createElement('option');
                    opt.value = subcat.id;
                    opt.textContent = subcat.name;
                    subcatSelect.appendChild(opt);
                });
                subcatSelect.value = subcategory;
            }
            document.getElementById('edit-service-price').value = price;
            document.getElementById('edit-service-delivery').value = delivery;
            // Show modal
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
});
