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

        if (type === "services") {
            // Make an AJAX request to fetch all services
            fetch('/api/api_all_services.php')
            .then(response => response.json())
            .then(services => {
                if (services.length > 0) {
                    services.forEach(service => {
                        const serviceCard = document.createElement('a');
                        serviceCard.href = `/pages/service.php?id=${encodeURIComponent(service.id)}`;
                        serviceCard.classList.add('service-card-link');
                        serviceCard.innerHTML = `
                            <div class="service-card" data-subcategory-ids="${encodeURIComponent(service.subcatIdsStr || '')}">
                                <div class="pantone-image-wrapper">
                                    ${service.image ? `<img src="${encodeURIComponent(service.image)}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                                </div>
                                <div class="pantone-title">${encodeURIComponent(service.title)}</div>
                                <div class="pantone-info-row">
                                    <span class="pantone-username">${encodeURIComponent(service.username)}</span>
                                    <span class="pantone-rating">★ ${encodeURIComponent(service.rating || '0.0')}</span>
                                </div>
                            </div>
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
    }

    // Load default results (services)
    loadSearchResults("services");
});