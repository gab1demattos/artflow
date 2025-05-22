document.addEventListener('DOMContentLoaded', () => {
    const smallImgs = document.querySelectorAll('.service-imgs');
    const mainImage = document.querySelector('#main-image img'); // Fixed query selector to target the correct element

    smallImgs.forEach(smallImg => {
        smallImg.addEventListener('click', () => {
            mainImage.src = smallImg.src; // Corrected variable usage
        });
    });
});