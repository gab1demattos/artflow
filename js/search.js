document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    searchInput.addEventListener('input', () => {
        searchButton.style.display = searchInput.value ? 'block' : 'none';
    });

    searchInput.addEventListener('focus', () => {
        searchButton.style.display = 'none';
    });

    searchInput.addEventListener('blur', () => {
        searchButton.style.display = 'block';
    });
});