// Handles the Edit Service modal logic: open, pre-fill, and submit

document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('edit-service-btn');
    const modal = document.getElementById('editServiceModal');
    const closeBtn = document.getElementById('closeEditServiceModal');
    const form = document.getElementById('editServiceForm');

    if (!editBtn || !modal || !closeBtn || !form) return;

    // Open modal and pre-fill fields
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
        document.getElementById('edit-service-price').value = price;
        document.getElementById('edit-service-delivery').value = delivery;

        // Populate categories and subcategories
        const catSelect = document.getElementById('edit-service-category');
        const subcatSelect = document.getElementById('edit-service-subcategory');
        if (typeof editSubcategoriesByCategory !== 'undefined') {
            // Set selected category
            if (category) catSelect.value = category;
            // Populate subcategories for selected category
            subcatSelect.innerHTML = '';
            if (editSubcategoriesByCategory[category]) {
                editSubcategoriesByCategory[category].forEach(function (subcat) {
                    const opt = document.createElement('option');
                    opt.value = subcat.id;
                    opt.textContent = subcat.name;
                    if (subcat.id == subcategory) opt.selected = true;
                    subcatSelect.appendChild(opt);
                });
            }
            // Update subcategories on category change
            catSelect.addEventListener('change', function () {
                const catId = this.value;
                subcatSelect.innerHTML = '';
                if (editSubcategoriesByCategory[catId]) {
                    editSubcategoriesByCategory[catId].forEach(function (subcat) {
                        const opt = document.createElement('option');
                        opt.value = subcat.id;
                        opt.textContent = subcat.name;
                        subcatSelect.appendChild(opt);
                    });
                }
            });
        }

        // Show the modal
        modal.style.display = 'block';
    });

    // Close modal
    closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Submit form
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        // Gather data from form
        const formData = new FormData(form);
        // Send data to server or handle it as needed
        // ...

        // Close the modal after submission
        modal.style.display = 'none';
    });
});
