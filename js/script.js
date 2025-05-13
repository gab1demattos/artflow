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

    // Store password in sessionStorage on signup form submit
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            const passwordInput = signupForm.querySelector('input[name="password"]');
            if (passwordInput) {
                sessionStorage.setItem('signup_password', passwordInput.value);
            }
        });
    }

    // Store username in sessionStorage on signup form submit (for fallback)
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            const usernameInput = signupForm.querySelector('input[name="username"]');
            if (usernameInput) {
                sessionStorage.setItem('signup_username', usernameInput.value);
            }
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
            // Show the choose role modal
            hideAllModals();
            document.getElementById('goflow-modal-overlay').classList.remove('hidden');

            // Clean the URL to prevent showing the modal again on refresh
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // Show go-with-flow modal if needed
    if (urlParams.get('modal') === 'go_with_flow') {
        hideAllModals();
        document.getElementById('goflow-modal-overlay').classList.remove('hidden');
        // Clean the URL
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }

    // Handle Go Flow modal arrow button click to log in
    const goFlowArrowButton = document.getElementById('go-arrow');
    if (goFlowArrowButton) {
        goFlowArrowButton.addEventListener('click', async function () {
            // Get username from URL (set by signup redirect)
            const urlParams = new URLSearchParams(window.location.search);
            let username = urlParams.get('username');
            if (!username) {
                // Try to get from previous signup form
                const lastUsername = sessionStorage.getItem('signup_username');
                if (lastUsername) username = lastUsername;
            }
            
            // Get password from sessionStorage
            const password = sessionStorage.getItem('signup_password');
            
            if (!username || !password) {
                alert('Could not log in automatically. Please sign in manually.');
                document.getElementById('goflow-modal-overlay').classList.add('hidden');
                return;
            }
            
            // Send AJAX POST to login
            try {
                const response = await fetch('/actions/signin-action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
                });
                
                // Check for a non-JSON response (your PHP returns a redirect, not JSON)
                const contentType = response.headers.get('content-type');
                if (response.ok) {
                    // Clean up sensitive info
                    sessionStorage.removeItem('signup_password');
                    sessionStorage.removeItem('signup_username');
                    // Redirect to index with logged_in parameter
                    window.location.href = '/index.php?logged_in=true';
                } else {
                    alert('Login failed. Please sign in manually.');
                    document.getElementById('goflow-modal-overlay').classList.add('hidden');
                }
            } catch (err) {
                console.error('Login error:', err);
                alert('Login error. Please sign in manually.');
                document.getElementById('goflow-modal-overlay').classList.add('hidden');
            }
        });
    }
});