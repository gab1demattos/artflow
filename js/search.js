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

    const searchServicesBtn = document.getElementById("search-services");
    const searchNamesBtn = document.getElementById("search-names");
    const searchResults = document.getElementById("search-results");

    // Toggle active class between buttons
    searchServicesBtn.addEventListener("click", () => {
        searchServicesBtn.classList.add("active");
        searchNamesBtn.classList.remove("active");
        loadSearchResults("services");
    });

    searchNamesBtn.addEventListener("click", () => {
        searchNamesBtn.classList.add("active");
        searchServicesBtn.classList.remove("active");
        loadSearchResults("names");
    });

    // Function to load search results dynamically
    function loadSearchResults(type) {
        searchResults.innerHTML = ""; // Clear previous results

        if (type === "services") {
            // Example: Load services dynamically (replace with actual data fetching)
            const services = ["Service 1", "Service 2", "Service 3", "Service 4"];
            services.forEach(service => {
                const div = document.createElement("div");
                div.textContent = service;
                searchResults.appendChild(div);
            });
        } else if (type === "names") {
            // Example: Load names dynamically (replace with actual data fetching)
            const names = ["Name 1", "Name 2", "Name 3", "Name 4"];
            names.forEach(name => {
                const div = document.createElement("div");
                div.textContent = name;
                searchResults.appendChild(div);
            });
        }
    }

    // Load default results (services)
    loadSearchResults("services");
});