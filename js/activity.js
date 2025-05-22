// JS for tab switching on the activity page
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.activity-tab');
    const contents = document.querySelectorAll('.activity-tab-content');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });
});
