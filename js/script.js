
// This script handles the modal functionality for the signup modal
document.addEventListener('DOMContentLoaded', function() {
    const signupBtn = document.querySelector('#buttons li button');
    const modalOverlay = document.getElementById('modal-overlay');
    
    signupBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        modalOverlay.classList.remove('hidden');
    });
    
    modalOverlay.addEventListener('click', function() {
        modalOverlay.classList.add('hidden');
    });
    
    document.getElementById('modal').addEventListener('click', function(e) {
        e.stopPropagation();
    });
});