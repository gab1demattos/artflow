document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const SearchInputPage = document.getElementById('search-page-input');
    const SearchBarPage = document.getElementById('search-page-bar');
    const searchServicesBtn = document.getElementById("search-services");
    const searchNamesBtn = document.getElementById("search-names");
    const searchResults = document.getElementById("search-results");
    const searchBar = document.getElementById('search-bar');
    const filterSearch = document.getElementById("filter-search");
    const deliveryTimeInput = document.getElementById('delivery-time');

    // Redirect to search.php when the search bar is clicked
    searchBar.addEventListener('click', () => {
        window.location.href = '../pages/search.php';
        searchBar.classList.add('hidden'); // Use a CSS class to hide the search bar
    });

    // Call initial() when the search.php page loads
    if (window.location.pathname.includes('search.php')) {
        initial("services"); // Default to "services" on page load
    }

    // Interrupt event listener when switching between buttons
    searchServicesBtn.addEventListener("click", () => {
        searchServicesBtn.classList.add("active");
        searchNamesBtn.classList.remove("active");
        searchResults.classList.add("services-active");
        searchResults.classList.remove("names-active");

        // Clear the search input when switching buttons
        SearchInputPage.value = "";

        // Reset filters when switching buttons
        const filterInputs = document.querySelectorAll('.filter-option-category input[type="checkbox"], .filter-option-rating input[type="checkbox"], #min-price, #max-price, #delivery-time');
        filterInputs.forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = true; // Set all checkboxes to checked
            } else if (input.id === 'min-price' || input.id === 'max-price') {
                input.value = input.id === 'min-price' ? input.min : input.max;// Set price inputs to initial values
            } else if (input.id === 'delivery-time') {
                input.value = input.max; // Set delivery time to max value
            }
        });

        // Reset price range inputs to their initial values
        minPriceInput.value = minPriceInput.min;
        maxPriceInput.value = maxPriceInput.max;

        // Update the displayed values
        minPriceDisplay.textContent = minPriceInput.min;
        maxPriceDisplay.textContent = maxPriceInput.max;

        // Trigger the price range update logic
        updatePriceRange();

        // Show filter-search when services are active
        filterSearch.classList.remove("hidden");

        // Add new listener for services
        loadSearchResults("services", SearchInputPage);
    });

    searchNamesBtn.addEventListener("click", () => {
        searchNamesBtn.classList.add("active");
        searchServicesBtn.classList.remove("active");
        searchResults.classList.add("names-active");
        searchResults.classList.remove("services-active");

        // Clear the search input when switching buttons
        SearchInputPage.value = "";

        // Reset filters when switching buttons
        const filterInputs = document.querySelectorAll('.filter-option-category input[type="checkbox"], .filter-option-rating input[type="checkbox"], #min-price, #max-price');
        filterInputs.forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = true; // Set all checkboxes to checked
            } else {
                input.value = input.id === 'min-price' ? '0' : '1000'; // Set price inputs to initial values
            }
        });

        // Hide filter-search when names are active
        filterSearch.classList.add("hidden");
        SearchBarPage.classList.add("names-active");

        // Add new listener for names
        loadSearchResults("names", SearchInputPage);
    });

    const handleSearchBarInteraction = () => {
        // Determine which button is active and call initial with the appropriate type
        if (searchServicesBtn.classList.contains("active")) {
            initial("services");
        } else if (searchNamesBtn.classList.contains("active")) {
            initial("names");
        }
    };

    searchInput.addEventListener('focus', handleSearchBarInteraction);
    searchServicesBtn.addEventListener('click', handleSearchBarInteraction);
    searchNamesBtn.addEventListener('click', handleSearchBarInteraction);

    searchInput.addEventListener('blur', () => {
        searchButton.style.display = 'block';
    });

    // Function to load search results dynamically
    function loadSearchResults(type, inputElement) {
        const listener = async function () {
            searchResults.innerHTML = ""; // Clear previous results
            const query = inputElement.value.trim();

            const endpoint = type === "services" 
                ? (query === '' ? '/api/api_all_services.php' : `/api/api_services.php?search=${encodeURIComponent(query)}`)
                : (query === '' ? '/api/api_users.php' : `/api/api_users.php?search=${encodeURIComponent(query)}`);

            try {
                const response = await fetch(endpoint);
                const data = await response.json();

                searchResults.innerHTML = ''; // Clear previous results
                if (data.length > 0) {
                    data.forEach(item => {
                        const card = document.createElement('a');
                        if (type === "services") {
                            card.href = `/pages/service.php?id=${encodeURIComponent(item.id)}`;
                            card.classList.add('service-card-link');
                            card.innerHTML = `
                                <div class="service-card">
                                    <div class="pantone-image-wrapper">
                                        ${item.image ? `<img src="${item.image}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                                    </div>
                                    <div class="pantone-title">${item.title}</div>
                                    <div class="pantone-info-row">
                                        <span class="pantone-username">${item.username}</span>
                                        <span class="pantone-rating">★ ${item.rating || '0.0'}</span>
                                    </div>
                                </div>
                            `;
                        } else {
                            card.href = `/pages/profile.php?username=${encodeURIComponent(item.username)}`;
                            card.classList.add('user-card-link');
                            card.innerHTML = `
                                <div class="user-card">
                                    <div class="user-info">
                                        <img src="${item.profilePicture || '/images/user_pfp/default.png'}" alt="User profile picture" class="user-profile-picture" />
                                        <p class="user-name">${item.name}</p>
                                        <p class="user-username">@${item.username}</p>
                                    </div>
                                </div>
                            `;
                        }
                        searchResults.appendChild(card);
                    });
                } else {
                    searchResults.innerHTML = ""; // Clear previous results
                    searchResults.innerHTML = `<p>No ${type} found.</p>`;
                }
            } catch (error) {
                console.error(`Error fetching search results for ${type}:`, error);
                searchResults.innerHTML = ""; // Clear previous results
                searchResults.innerHTML = `<p>Error loading search results. Please try again later.</p>`;
            }
        };

        inputElement._listener = listener;
        inputElement.addEventListener('input', listener);
    }

    function initial(type) {
        searchResults.innerHTML = ""; // Clear previous results

        if (type === "services") {
            // Make an AJAX request to fetch all services
            fetch('/api/api_all_services.php')
            .then(response => response.json())
            .then(services => {
                searchResults.innerHTML = ""; // Clear previous results
                if (services.length > 0) {
                    services.forEach(service => {
                        const serviceCard = document.createElement('a');
                        serviceCard.href = `/pages/service.php?id=${encodeURIComponent(service.id)}`;
                        serviceCard.classList.add('service-card-link');
                        serviceCard.innerHTML = `
                            <div class="service-card" data-subcategory-ids="${encodeURIComponent(service.subcatIdsStr || '')}">
                                <div class="pantone-image-wrapper">
                                    ${service.image ? `<img src="${service.image}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                                </div>
                                <div class="pantone-title">${service.title}</div>
                                <div class="pantone-info-row">
                                    <span class="pantone-username">${service.username}</span>
                                    <span class="pantone-rating">★ ${service.rating || '0.0'}</span>
                                </div>
                            </div>
                        `;
                        searchResults.appendChild(serviceCard);
                    });
                } else {
                    searchResults.innerHTML = ""; // Clear previous results
                    searchResults.innerHTML = '<p>No services found.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching services:', error);
                searchResults.innerHTML = ""; // Clear previous results
                searchResults.innerHTML = '<p>Error loading services. Please try again later.</p>';
            });
        } else if (type === "names") {
            // Make an AJAX request to fetch all users
            fetch('/api/api_users.php')
            .then(response => response.json())
            .then(users => {
                searchResults.innerHTML = ""; // Clear previous results
                if (users.length > 0) {
                    users.forEach(user => {
                        const userCard = document.createElement('a');
                        userCard.href = `/pages/profile.php?username=${encodeURIComponent(user.username)}`;
                        userCard.classList.add('user-card-link');
                        userCard.innerHTML = `
                            <div class="user-card">
                                <div class="user-info">
                                    <img src="${user.profilePicture || '/images/user_pfp/default.png'}" alt="User profile picture" class="user-profile-picture" />
                                    <p class="user-username">${user.name}</p>
                                    <p class="user-username">@${user.username}</p>
                                </div>
                            </div>
                        `;
                        searchResults.appendChild(userCard);
                    });
                } else {
                    searchResults.innerHTML = ""; // Clear previous results
                    searchResults.innerHTML = '<p>No users found.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                searchResults.innerHTML = ""; // Clear previous results
                searchResults.innerHTML = '<p>Error loading users. Please try again later.</p>';
            });
        }
    }

    // Load default results (services)
    loadSearchResults("services", SearchInputPage);

    const categoryCheckboxes = document.querySelectorAll('.filter-option-category input[type="checkbox"]');

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', async () => {
            const selectedCategories = Array.from(categoryCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.id.replace('filter-option-', ''));

            console.log('Selected Categories:', selectedCategories);

            // Fetch services dynamically based on selected categories
            try {
                const response = await fetch(`/api/api_services.php?categories=${selectedCategories.join(',')}`);
                const services = await response.json();

                const searchResults = document.getElementById('search-results');
                searchResults.innerHTML = ""; // Clear previous results

                if (services.length > 0) {
                    services.forEach(service => {
                        const serviceCard = document.createElement('a');
                        serviceCard.href = `/pages/service.php?id=${encodeURIComponent(service.id)}`;
                        serviceCard.classList.add('service-card-link');
                        serviceCard.innerHTML = `
                            <div class="service-card">
                                <div class="pantone-image-wrapper">
                                    ${service.image ? `<img src="${service.image}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                                </div>
                                <div class="pantone-title">${service.title}</div>
                                <div class="pantone-info-row">
                                    <span class="pantone-username">${service.username}</span>
                                    <span class="pantone-rating">★ ${service.rating || '0.0'}</span>
                                </div>
                            </div>
                        `;
                        searchResults.appendChild(serviceCard);
                    });
                } else {
                    searchResults.innerHTML = ""; // Clear previous results
                    searchResults.innerHTML = '<p>No services found for the selected categories.</p>';
                }
            } catch (error) {
                console.error('Error fetching services:', error);
                searchResults.innerHTML = ""; // Clear previous results
                searchResults.innerHTML = '<p>Error loading services. Please try again later.</p>';
            }
        });
    });

    // Add event listeners for price range inputs
    const minPriceInput = document.querySelector('.min-price');
    const maxPriceInput = document.querySelector('.max-price');
    const minPriceDisplay = document.getElementById('min-value');
    const maxPriceDisplay = document.getElementById('max-value');

    const updatePriceRange = async () => {
        let minPrice = parseFloat(minPriceInput.value);
        let maxPrice = parseFloat(maxPriceInput.value);

        if (minPrice > maxPrice) {
            minPrice = maxPrice;
            minPriceInput.value = minPrice;
        }

        minPriceDisplay.textContent = minPrice;
        maxPriceDisplay.textContent = maxPrice;

        const selectedCategories = Array.from(document.querySelectorAll('.filter-option-category input[type="checkbox"]'))
            .filter(cb => cb.checked)
            .map(cb => cb.id.replace('filter-option-', ''));

        console.log('Selected Categories:', selectedCategories);
        console.log('Price Range:', minPrice, maxPrice);

        try {
            const response = await fetch(`/api/api_services.php?categories=${selectedCategories.join(',')}&min_price=${minPrice}&max_price=${maxPrice}`);
            const services = await response.json();

            searchResults.innerHTML = ""; // Clear previous results

            if (services.length > 0) {
                services.forEach(service => {
                    const serviceCard = document.createElement('a');
                    serviceCard.href = `/pages/service.php?id=${encodeURIComponent(service.id)}`;
                    serviceCard.classList.add('service-card-link');
                    serviceCard.innerHTML = `
                        <div class="service-card">
                            <div class="pantone-image-wrapper">
                                ${service.image ? `<img src="${service.image}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                            </div>
                            <div class="pantone-title">${service.title}</div>
                            <div class="pantone-info-row">
                                <span class="pantone-username">${service.username}</span>
                                <span class="pantone-rating">★ ${service.rating || '0.0'}</span>
                            </div>
                        </div>
                    `;
                    searchResults.appendChild(serviceCard);
                });
            } else {
                searchResults.innerHTML = '<p>No services found for the selected filters.</p>';
            }
        } catch (error) {
            console.error('Error fetching services:', error);
            searchResults.innerHTML = '<p>Error loading services. Please try again later.</p>';
        }
    };

    minPriceInput.addEventListener('input', () => {
        if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
            minPriceInput.value = maxPriceInput.value;
        }
        updatePriceRange();
    });

    maxPriceInput.addEventListener('input', () => {
        console.log('Max Price Input Changed:', maxPriceInput.value); // Debugging log
        if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
            maxPriceInput.value = minPriceInput.value;
        }
        maxPriceDisplay.textContent = maxPriceInput.value; // Ensure maxPriceDisplay is updated
        updatePriceRange();
    });

    deliveryTimeInput.addEventListener('change', async () => {
        const maxDeliveryTime = parseFloat(deliveryTimeInput.value);

        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.id.replace('filter-option-', ''));

        const minPrice = parseFloat(minPriceInput.value);
        const maxPrice = parseFloat(maxPriceInput.value);

        console.log('Selected Categories:', selectedCategories);
        console.log('Price Range:', minPrice, maxPrice);
        console.log('Max Delivery Time:', maxDeliveryTime);

        // Fetch services dynamically based on selected categories, price range, and delivery time
        try {
            const response = await fetch(`/api/api_services.php?categories=${selectedCategories.join(',')}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}`);
            const services = await response.json();

            searchResults.innerHTML = ""; // Clear previous results

            if (services.length > 0) {
                services.forEach(service => {
                if (service.delivery_time <= maxDeliveryTime) { // Ensure delivery time filter is applied
                    const serviceCard = document.createElement('a');
                    serviceCard.href = `/pages/service.php?id=${encodeURIComponent(service.id)}`;
                    serviceCard.classList.add('service-card-link');
                    serviceCard.innerHTML = `
                        <div class="service-card">
                            <div class="pantone-image-wrapper">
                                ${service.image ? `<img src="${service.image}" alt="Service image" class="pantone-image" />` : '<div class="pantone-image pantone-image-placeholder"></div>'}
                            </div>
                            <div class="pantone-title">${service.title}</div>
                            <div class="pantone-info-row">
                                <span class="pantone-username">${service.username}</span>
                                <span class="pantone-rating">★ ${service.rating || '0.0'}</span>
                            </div>
                        </div>
                    `;
                    searchResults.appendChild(serviceCard);
}
                });
            } else {
                searchResults.innerHTML = ""; // Clear previous results
                searchResults.innerHTML = '<p>No services found for the selected filters.</p>';
            }
        } catch (error) {
            console.error('Error fetching services:', error);
            searchResults.innerHTML = ""; // Clear previous results
            searchResults.innerHTML = '<p>Error loading services. Please try again later.</p>';
        }
    });

    // Fetch the maximum delivery time dynamically and set it as the default value
    async function setDefaultDeliveryTime() {
        try {
            const response = await fetch('/api/api_all_services.php');
            const services = await response.json();

            if (services.length > 0) {
                const maxDeliveryTime = Math.max(...services.map(service => service.delivery_time));
                deliveryTimeInput.value = maxDeliveryTime;
            }
        } catch (error) {
            console.error('Error fetching services for default delivery time:', error);
        }
    }

    setDefaultDeliveryTime();
});





