document.addEventListener('DOMContentLoaded', function() {
    // Button elements
    const signupBtn = document.querySelector('#buttons li button');
    const signInButtons = document.querySelectorAll('#sign-in');
    const signUpButtons = document.querySelectorAll('#sign-up');
    const signUpBtn_submit = document.querySelector('#sign-up-submit');
    
    // Role selection elements
    const clientRole = document.getElementById('client-role');
    const clientCheckbox = document.getElementById('client-checkbox');
    const freelancerRole = document.getElementById('freelancer-role');
    const freelancerCheckbox = document.getElementById('freelancer-checkbox');

    // Function to hide all modals
    function hideAllModals() {
        document.getElementById('signup-modal-overlay')?.classList.add('hidden');
        document.getElementById('signin-modal-overlay')?.classList.add('hidden');
        document.getElementById('choose-role-modal-overlay')?.classList.add('hidden');
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

    // Handle sign up form submission
    if (signUpBtn_submit) {
        signUpBtn_submit.addEventListener('click', function(e) {
            e.preventDefault();
            hideAllModals();
            document.getElementById('choose-role-modal-overlay').classList.remove('hidden');
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

    // Toggle client checkbox when clicking the client role box
    if (clientRole && clientCheckbox) {
        clientRole.addEventListener('click', function() {
            clientCheckbox.checked = !clientCheckbox.checked;
        });
    }

    // Toggle freelancer checkbox when clicking the freelancer role box
    if (freelancerRole && freelancerCheckbox) {
        freelancerRole.addEventListener('click', function() {
            freelancerCheckbox.checked = !freelancerCheckbox.checked;
        });
    }
});