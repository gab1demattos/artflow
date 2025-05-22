// Handles the checkout modal flow for service purchase

document.addEventListener('DOMContentLoaded', function () {
    const paymentBtn = document.getElementById('payment');
    const requirementsModal = document.getElementById('requirements-modal-overlay');
    const requirementsClose = document.getElementById('close-requirements-modal');
    const requirementsContinue = document.getElementById('requirements-continue');
    const requirementsTextarea = document.getElementById('requirements-textarea');

    const paymentModal = document.getElementById('payment-modal-overlay');
    const paymentClose = document.getElementById('close-payment-modal');
    const paymentConfirm = document.getElementById('payment-confirm');
    const paymentForm = document.getElementById('payment-form');

    // Open requirements modal
    if (paymentBtn && requirementsModal) {
        paymentBtn.addEventListener('click', function () {
            requirementsModal.classList.remove('hidden');
        });
    }

    // Close requirements modal
    if (requirementsClose && requirementsModal) {
        requirementsClose.addEventListener('click', function () {
            requirementsModal.classList.add('hidden');
        });
    }

    // Fill order overview in payment modal
    function fillOrderOverview() {
        // Get data from DOM
        const serviceTitle = document.querySelector('#service-checkout h2')?.textContent || '';
        const ownerName = document.querySelector('#owner-info p')?.textContent || '';
        const delivery = document.querySelector('#service-delivery .service-delivery:last-child')?.textContent || '';
        const price = document.getElementById('price')?.textContent?.replace('€', '').trim() || '';
        const requirements = document.getElementById('requirements-textarea')?.value || '';

        document.querySelector('#order-title span').textContent = serviceTitle;
        document.querySelector('#order-owner span').textContent = ownerName;
        document.querySelector('#order-delivery span').textContent = delivery;
        document.querySelector('#order-total').textContent = price;
        document.querySelector('#order-requirements span').textContent = requirements;
    }

    // Continue to payment modal
    if (requirementsContinue && requirementsModal && paymentModal) {
        requirementsContinue.addEventListener('click', function () {
            if (!requirementsTextarea.value.trim()) {
                requirementsTextarea.classList.add('input-error');
                requirementsTextarea.focus();
                return;
            }
            requirementsTextarea.classList.remove('input-error');
            requirementsModal.classList.add('hidden');
            paymentModal.classList.remove('hidden');
            fillOrderOverview();
        });
    }

    // Close payment modal
    if (paymentClose && paymentModal) {
        paymentClose.addEventListener('click', function () {
            paymentModal.classList.add('hidden');
        });
    }

    // Confirm payment (fake)
    if (paymentConfirm && paymentForm) {
        paymentConfirm.addEventListener('click', function (e) {
            e.preventDefault();
            // Simple validation
            const card = paymentForm.elements['card'].value.trim();
            const name = paymentForm.elements['name'].value.trim();
            const exp = paymentForm.elements['exp'].value.trim();
            const cvv = paymentForm.elements['cvv'].value.trim();
            if (!card || !name || !exp || !cvv) {
                paymentForm.classList.add('input-error');
                return;
            }
            paymentForm.classList.remove('input-error');
            paymentModal.classList.add('hidden');
            alert('Order placed! (Fake checkout)');
        });
    }
});
