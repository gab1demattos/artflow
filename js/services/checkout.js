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
    
    function toggleBodyScroll(disable) {
        if (disable) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    
    if (paymentBtn && requirementsModal) {
        paymentBtn.addEventListener('click', function () {
            requirementsModal.classList.remove('hidden');
            toggleBodyScroll(true);
        });
    }
    
    if (requirementsClose && requirementsModal) {
        requirementsClose.addEventListener('click', function () {
            requirementsModal.classList.add('hidden');
            toggleBodyScroll(false);
        });
    }
    
    if (requirementsModal) {
        requirementsModal.addEventListener('click', function (e) {
            if (e.target === requirementsModal) {
                requirementsModal.classList.add('hidden');
                toggleBodyScroll(false);
            }
        });
    }
    
    function fillOrderOverview() {
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
            toggleBodyScroll(true);
            fillOrderOverview();
        });
    }
    
    if (paymentClose && paymentModal) {
        paymentClose.addEventListener('click', function () {
            paymentModal.classList.add('hidden');
            toggleBodyScroll(false);
        });
    }
    
    if (paymentModal) {
        paymentModal.addEventListener('click', function (e) {
            if (e.target === paymentModal) {
                paymentModal.classList.add('hidden');
                toggleBodyScroll(false);
            }
        });
    }
    
    const cardInput = paymentForm ? paymentForm.elements['card'] : null;
    if (cardInput) {
        cardInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            e.target.value = value.substring(0, 19);
        });
    }
    
    const expInput = paymentForm ? paymentForm.elements['exp'] : null;
    if (expInput) {
        expInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\//g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
    
    if (paymentConfirm && paymentForm) {
        paymentConfirm.addEventListener('click', async function (e) {
            e.preventDefault();
            
            const card = paymentForm.elements['card'].value.trim();
            const name = paymentForm.elements['name'].value.trim();
            const exp = paymentForm.elements['exp'].value.trim();
            const cvv = paymentForm.elements['cvv'].value.trim();
            
            const cardPattern = /^(?:\d{4} ?){3}\d{4}$/;
            const expPattern = /^(0[1-9]|1[0-2])\/(\d{2})$/;
            const cvvPattern = /^\d{3}$/;
            
            let valid = true;
            let firstInvalid = null;
            if (!cardPattern.test(card)) {
                paymentForm.elements['card'].classList.add('input-error');
                valid = false;
                firstInvalid = firstInvalid || paymentForm.elements['card'];
            } else {
                paymentForm.elements['card'].classList.remove('input-error');
            }
            if (!expPattern.test(exp)) {
                paymentForm.elements['exp'].classList.add('input-error');
                valid = false;
                firstInvalid = firstInvalid || paymentForm.elements['exp'];
            } else {
                paymentForm.elements['exp'].classList.remove('input-error');
            }
            if (!cvvPattern.test(cvv)) {
                paymentForm.elements['cvv'].classList.add('input-error');
                valid = false;
                firstInvalid = firstInvalid || paymentForm.elements['cvv'];
            } else {
                paymentForm.elements['cvv'].classList.remove('input-error');
            }
            if (!name) {
                paymentForm.elements['name'].classList.add('input-error');
                valid = false;
                firstInvalid = firstInvalid || paymentForm.elements['name'];
            } else {
                paymentForm.elements['name'].classList.remove('input-error');
            }
            if (!valid) {
                if (firstInvalid) firstInvalid.focus();
                return;
            }
            const serviceId = new URLSearchParams(window.location.search).get('id');
            const requirements = document.getElementById('requirements-textarea')?.value || '';
            const price = document.getElementById('price')?.textContent?.replace('€', '').trim() || '';
            const delivery = document.querySelector('#service-delivery .service-delivery:last-child')?.textContent || '';
            let deliveryTime = 0;
            if (delivery) {
                const match = delivery.match(/(\d+)/);
                if (match) deliveryTime = parseInt(match[1], 10);
            }
            try {
                const response = await fetch('../../actions/service/create-order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: `service_id=${encodeURIComponent(serviceId)}&requirements=${encodeURIComponent(requirements)}&price=${encodeURIComponent(price)}&delivery_time=${encodeURIComponent(deliveryTime)}`
                });
                const result = await response.json();
                if (result.success) {
                    paymentForm.classList.remove('input-error');
                    paymentModal.classList.add('hidden');
                   
                    const thankyouModal = document.getElementById('thankyou-modal-overlay');
                    if (thankyouModal) {
                        thankyouModal.classList.remove('hidden');
                        const thankyouClose = document.getElementById('thankyou-close');
                        if (thankyouClose) {
                            thankyouClose.onclick = function () {
                                thankyouModal.classList.add('hidden');
                                toggleBodyScroll(false);
                                window.location.reload();
                            };
                        }
                       
                        thankyouModal.onclick = function (e) {
                            if (e.target === thankyouModal) {
                                thankyouModal.classList.add('hidden');
                                toggleBodyScroll(false);
                                window.location.reload();
                            }
                        };
                    }
                } else {
                    alert(result.error || 'Failed to place order.');
                }
            } catch (err) {
                alert('Error placing order. Please try again.');
            }
        });
    }
});
