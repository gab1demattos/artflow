document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    const searchServicesBtn = document.getElementById("search-services");
    const searchNamesBtn = document.getElementById("search-names");
    const searchResults = document.getElementById("search-results");

    searchInput.addEventListener('focus', () => {
        searchButton.style.display = 'none';
        if (searchInput.value.trim() !== "") {
            searchResults.classList.remove('empty-input');
        } else {
            searchResults.classList.add('empty-input');
        }
    });

    searchInput.addEventListener('blur', () => {
        searchButton.style.display = 'block';
    });

    searchInput.addEventListener('input', () => {
        if (searchInput.value.trim() === "") {
            searchResults.classList.add('empty-input');
        } else {
            searchResults.classList.remove('empty-input');
        }
    });

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

        if (searchResults.classList.contains("empty-input")) {
            if (type === "services") {
                // Make an AJAX request to fetch all services
                fetch('/api/api_all_services.php')
                    .then(response => response.json())
                    .then(services => {
                        if (services.length > 0) {
                            services.forEach(service => {
                                const serviceCard = document.createElement('div');
                                serviceCard.classList.add('service-card');
                                serviceCard.innerHTML = `
                                    <img src="${service.image}" alt="${service.name}">
                                    <h3>${service.name}</h3>
                                    <p>${service.description}</p>
                                `;
                                searchResults.appendChild(serviceCard);
                            });
                        } else {
                            searchResults.innerHTML = '<p>No services found.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching services:', error);
                        searchResults.innerHTML = '<p>Error loading services. Please try again later.</p>';
                    });
            } else if (type === "names") {
                searchResults.innerHTML = "<p>No names found. Please enter a search term.</p>";
            }
            return;
        }

        else if (type === "services") {
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