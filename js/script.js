document.addEventListener('DOMContentLoaded', function () {
    // Button elements
    const signupBtn = document.querySelector('#buttons li button');
    const signInButtons = document.querySelectorAll('#sign-in');
    const signUpButtons = document.querySelectorAll('#sign-up');
    const signUpBtn_submit = document.querySelector('#sign-up-submit');
    const nextBtn = document.querySelector('#next-btn');


    // Function to hide all modals
    function hideAllModals() {
        document.getElementById('signup-modal-overlay')?.classList.add('hidden');
        document.getElementById('signin-modal-overlay')?.classList.add('hidden');
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

    

    // Check for successful signup and show role selection modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('signup') === 'success') {
        const username = urlParams.get('username');
        if (username) {
            // Update the username in the modal if needed

            //const usernameElement = document.querySelector('.intro-text p:first-child');
            // if (usernameElement) {
            //     usernameElement.textContent = `Welcome, ${username}`;
            // }

            // Show the choose role modal
            hideAllModals();
            document.getElementById('goflow-modal-overlay').classList.remove('hidden');

            // Clean the URL to prevent showing the modal again on refresh
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    
    

    
});