document.addEventListener('DOMContentLoaded', () => {
    const smallImgs = document.querySelectorAll('.service-imgs');
    const mainImage = document.querySelector('#main-image img');

    smallImgs.forEach(smallImg => {
        smallImg.addEventListener('click', () => {
            mainImage.src = smallImg.src;
        });
    });
});