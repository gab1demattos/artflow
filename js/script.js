document.addEventListener('DOMContentLoaded', function() {
    const signupBtn = document.querySelector('#buttons li button');
    const modalOverlays = document.querySelectorAll('#modal-overlay');
    const signInButtons = document.querySelectorAll('#sign-in');
    const signUpButtons = document.querySelectorAll('#sign-up');
    const signUpBtn_submit = document.querySelector('#sign-up-submit');
    
    const clientRole = document.getElementById('client-role');
    const clientCheckbox = document.getElementById('client-checkbox');
    const freelancerRole = document.getElementById('freelancer-role');
    const freelancerCheckbox = document.getElementById('freelancer-checkbox');

    // Show sign up modal when clicking sign up button
    signupBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Hide all modals first
        modalOverlays.forEach(overlay => overlay.classList.add('hidden'));
        // Show sign up modal (the first one in DOM)
        document.querySelector('#signup-modal').closest('#modal-overlay').classList.remove('hidden');
    });

    // Handle click on sign in button in sign up modal
    signInButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            // Hide all modals first
            modalOverlays.forEach(overlay => overlay.classList.add('hidden'));
            // Show sign in modal
            document.querySelector('#signin-modal').closest('#modal-overlay').classList.remove('hidden');
        });
    });

    // Handle click on sign up button in sign in modal
    signUpButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            // Hide all modals first
            modalOverlays.forEach(overlay => overlay.classList.add('hidden'));
            // Show sign up modal
            document.querySelector('#signup-modal').closest('#modal-overlay').classList.remove('hidden');
        });
    });

    // Handle sign up form submission
    signUpBtn_submit.addEventListener('click', function(e) {
        e.preventDefault();
        // Hide all modals first
        modalOverlays.forEach(overlay => overlay.classList.add('hidden'));
        // Show success message (or handle form submission)
        document.querySelector('#choose-role-modal').closest('#modal-overlay').classList.remove('hidden');
    });

    // Close modal when clicking outside
    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', function() {
            overlay.classList.add('hidden');
        });

        // Prevent modal from closing when clicking inside
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