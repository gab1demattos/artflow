document.addEventListener('DOMContentLoaded', function() {
    const signupBtn = document.querySelector('#buttons li button');
    const modalOverlays = document.querySelectorAll('#modal-overlay');
    const signInButtons = document.querySelectorAll('#sign-in');
    const signUpButtons = document.querySelectorAll('#sign-up');
    
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
});