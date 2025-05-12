document.addEventListener('DOMContentLoaded', function () {
    // Button elements
    const signupBtn = document.querySelector('#buttons li button');
    const signInButtons = document.querySelectorAll('#sign-in');
    const signUpButtons = document.querySelectorAll('#sign-up');
    const signUpBtn_submit = document.querySelector('#sign-up-submit');
    const nextBtn = document.querySelector('#next-btn');

    // Role selection elements (keep these for future use)
    const clientRole = document.getElementById('client-role');
    const clientCheckbox = document.getElementById('client-checkbox');
    const freelancerRole = document.getElementById('freelancer-role');
    const freelancerCheckbox = document.getElementById('freelancer-checkbox');

    // Function to hide all modals
    function hideAllModals() {
        document.getElementById('signup-modal-overlay')?.classList.add('hidden');
        document.getElementById('signin-modal-overlay')?.classList.add('hidden');
        document.getElementById('choose-role-modal-overlay')?.classList.add('hidden');
        document.getElementById('goflow-modal-overlay')?.classList.add('hidden');
    }

    // Show sign up modal when clicking sign up button
    if (signupBtn) {
        signupBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signup-modal-overlay').classList.remove('hidden');
        });
    }

    // Handle click on sign in button in sign up modal
    signInButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signin-modal-overlay').classList.remove('hidden');
        });
    });

    // Handle click on sign up button in sign in modal
    signUpButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signup-modal-overlay').classList.remove('hidden');
        });
    });

    // MODIFIED: Handle sign up form submission (direct submit without role selection)
    if (signUpBtn_submit) {
        signUpBtn_submit.addEventListener('click', function (e) {
            // Get the form element
            const form = document.getElementById('signup-form');

            // Basic client-side validation
            const password = form.querySelector('input[name="password"]').value;
            const confirmPassword = form.querySelector('input[name="confirm_password"]').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                e.preventDefault();
                return;
            }

            // If validation passes, let the form submit normally
            // All role-related code has been removed for now
        });
    }

    // Close modal when clicking outside
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function () {
            overlay.classList.add('hidden');
        });

        overlay.querySelector('.modal').addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // Role selection implementation
    if (clientRole && clientCheckbox) {
        clientRole.addEventListener('click', function (e) {
            // Toggle the checkbox state
            clientCheckbox.checked = !clientCheckbox.checked;

            // Force a change event on the checkbox to ensure the CSS styles apply
            const event = new Event('change', { bubbles: true });
            clientCheckbox.dispatchEvent(event);

            // Apply visual styling directly to ensure it works
            if (clientCheckbox.checked) {
                clientRole.style.backgroundColor = 'var(--yellow)';
                clientRole.style.color = 'white';
            } else {
                clientRole.style.backgroundColor = '';
                clientRole.style.color = 'var(--yellow)';
            }

            console.log('Client role clicked, checked:', clientCheckbox.checked);
        });

        // Also listen for checkbox change events
        clientCheckbox.addEventListener('change', function () {
            if (this.checked) {
                clientRole.style.backgroundColor = 'var(--yellow)';
                clientRole.style.color = 'white';
            } else {
                clientRole.style.backgroundColor = '';
                clientRole.style.color = 'var(--yellow)';
            }
        });
    }

    if (freelancerRole && freelancerCheckbox) {
        freelancerRole.addEventListener('click', function (e) {
            // Toggle the checkbox state
            freelancerCheckbox.checked = !freelancerCheckbox.checked;

            // Force a change event on the checkbox to ensure the CSS styles apply
            const event = new Event('change', { bubbles: true });
            freelancerCheckbox.dispatchEvent(event);

            // Apply visual styling directly to ensure it works
            if (freelancerCheckbox.checked) {
                freelancerRole.style.backgroundColor = 'var(--green)';
                freelancerRole.style.color = 'white';
            } else {
                freelancerRole.style.backgroundColor = '';
                freelancerRole.style.color = 'var(--green)';
            }

            console.log('Freelancer role clicked, checked:', freelancerCheckbox.checked);
        });

        // Also listen for checkbox change events
        freelancerCheckbox.addEventListener('change', function () {
            if (this.checked) {
                freelancerRole.style.backgroundColor = 'var(--green)';
                freelancerRole.style.color = 'white';
            } else {
                freelancerRole.style.backgroundColor = '';
                freelancerRole.style.color = 'var(--green)';
            }
        });
    }

    // Keep this for future role selection implementation
    function resetRoleSelection() {
        if (clientCheckbox) clientCheckbox.checked = false;
        if (freelancerCheckbox) freelancerCheckbox.checked = false;
    }

    // Check for successful signup and show role selection modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('signup') === 'success') {
        const username = urlParams.get('username');
        if (username) {
            // Update the username in the modal if needed
            const usernameElement = document.querySelector('.intro-text p:first-child');
            if (usernameElement) {
                usernameElement.textContent = `Welcome, ${username}`;
            }

            // Show the choose role modal
            hideAllModals();
            document.getElementById('choose-role-modal-overlay').classList.remove('hidden');

            // Clean the URL to prevent showing the modal again on refresh
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // Handle Next button click in role selection modal
    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default button behavior

            // Check if at least one role is selected
            if (!clientCheckbox.checked && !freelancerCheckbox.checked) {
                alert('Please select at least one role.');
                return;
            }

            // Create form data
            const formData = new FormData();
            if (clientCheckbox.checked) {
                formData.append('client', '1');
            }
            if (freelancerCheckbox.checked) {
                formData.append('freelancer', '1');
            }

            // Send form data to action_updaterole.php
            fetch('actions/action_updaterole.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (response.ok || response.redirected) {
                        window.location = 'index.php?role_updated=success';
                    } else {
                        console.error('Role update failed');
                        alert('Failed to update role. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });
    }

    // Avoid duplicate event listeners - already handled above

    document.getElementById('choose-role-modal-overlay')?.addEventListener('click', function (e) {
        if (e.target === this) {
            resetRoleSelection();
        }
    });
});