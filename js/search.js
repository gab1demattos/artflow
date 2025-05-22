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
                searchResults.innerHTML = `
                <div id="services-list">
                    <?php
                    // Fetch all services for this category using Service class method
                    $services = Service::getAllServices();
                    if ($services) {
                        foreach ($services as $serviceObj) {
                            // Get subcategory IDs for this service
                            $subcatIds = $serviceObj->getSubcategoryIds();
                            $subcatIdsStr = implode(',', $subcatIds);
                            
                            // Get first image for this service
                            $serviceImage = $serviceObj->getFirstImage();
                            
                            // Convert service object to array for the template
                            $service = $serviceObj->toArray();
                            
                            // Use the service card component
                            drawServiceCard($service, $serviceImage, $subcatIdsStr);
                        }
                    } else {
                        echo '<p>No services found in this category yet.</p>';
                    }
                    ?>
                </div>`;
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