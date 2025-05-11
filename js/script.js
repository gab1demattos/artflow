document.addEventListener('DOMContentLoaded', function() {
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
        signupBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signup-modal-overlay').classList.remove('hidden');
        });
    }

    // Handle click on sign in button in sign up modal
    signInButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signin-modal-overlay').classList.remove('hidden');
        });
    });

    // Handle click on sign up button in sign in modal
    signUpButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            hideAllModals();
            document.getElementById('signup-modal-overlay').classList.remove('hidden');
        });
    });

    // MODIFIED: Handle sign up form submission (direct submit without role selection)
    if (signUpBtn_submit) {
        signUpBtn_submit.addEventListener('click', function(e) {
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
        overlay.addEventListener('click', function() {
            overlay.classList.add('hidden');
        });

        overlay.querySelector('.modal').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Keep these for future role selection implementation
    if (clientRole && clientCheckbox) {
        clientRole.addEventListener('click', function() {
            clientCheckbox.checked = !clientCheckbox.checked;
        });
    }

    if (freelancerRole && freelancerCheckbox) {
        freelancerRole.addEventListener('click', function() {
            freelancerCheckbox.checked = !freelancerCheckbox.checked;
        });
    }

    // Keep this for future role selection implementation
    function resetRoleSelection() {
        if (clientCheckbox) clientCheckbox.checked = false;
        if (freelancerCheckbox) freelancerCheckbox.checked = false;
    }

    /*
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedRoles = [];
            
            if (clientCheckbox.checked) {
                selectedRoles.push('Client');
            }
            if (freelancerCheckbox.checked) {
                selectedRoles.push('Freelancer');
            }

            if (selectedRoles.length === 0) {
                alert('Please select at least one role.');
                return;
            }

            console.log('Selected roles stored:', selectedRoles);
            hideAllModals();
            document.getElementById('goflow-modal-overlay')?.classList.remove('hidden');
        });
    }

    document.getElementById('choose-role-modal-overlay')?.addEventListener('click', function(e) {
        if (e.target === this) {
            resetRoleSelection();
        }
    });
    */
});