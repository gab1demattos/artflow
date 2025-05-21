// JavaScript to make the service-imgs div scrollable

document.addEventListener('DOMContentLoaded', () => {
    const serviceImgs = document.getElementById('service-imgs');

    if (serviceImgs) {
        serviceImgs.style.overflowX = 'scroll';
        serviceImgs.style.whiteSpace = 'nowrap';

        // Optional: Add smooth scrolling behavior
        serviceImgs.style.scrollBehavior = 'smooth';

        // Optional: Add event listeners for custom scrolling (e.g., buttons)
        // Example:
        // const scrollLeftButton = document.getElementById('scroll-left');
        // const scrollRightButton = document.getElementById('scroll-right');
        // scrollLeftButton.addEventListener('click', () => {
        //     serviceImgs.scrollBy({ left: -100, behavior: 'smooth' });
        // });
        // scrollRightButton.addEventListener('click', () => {
        //     serviceImgs.scrollBy({ left: 100, behavior: 'smooth' });
        // });
    }
});