document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    const searchServicesBtn = document.getElementById("search-services");
    const searchNamesBtn = document.getElementById("search-names");
    const searchResults = document.getElementById("search-results");

    searchInput.addEventListener('focus', () => {
        searchButton.style.display = 'none';
    });

    searchInput.addEventListener('blur', () => {
        searchButton.style.display = 'block';
    });

    // Toggle active class between buttons
    searchServicesBtn.addEventListener("click", () => {
        searchServicesBtn.classList.add("active");
        searchNamesBtn.classList.remove("active");
        searchResults.classList.add("services-active");
        searchResults.classList.remove("names-active");
        loadSearchResults("services");
    });

    searchNamesBtn.addEventListener("click", () => {
        searchNamesBtn.classList.add("active");
        searchServicesBtn.classList.remove("active");
        searchResults.classList.add("names-active");
        searchResults.classList.remove("services-active");
        searchResults.scrollTop = 0; // Reset scroll position to the top
        loadSearchResults("names");
    });

    // Function to load search results dynamically
    function loadSearchResults(type) {
        searchResults.innerHTML = ""; // Clear previous results

        searchInput.addEventListener('input', async function () {
            const query = searchInput.value.trim();

            if (query === '') {
                const endpoint = type === "services" ? '/api/api_all_services.php' : '/api/api_users.php';
                fetch(endpoint)
                    .then(response => response.json())
                    .then(data => {
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
                            searchResults.innerHTML = `<p>No ${type} found.</p>`;
                        }
                    })
                    .catch(error => {
                        console.error(`Error fetching ${type}:`, error);
                        searchResults.innerHTML = `<p>Error loading ${type}. Please try again later.</p>`;
                    });
            } else {
                const endpoint = type === "services" ? `/api/api_all_services.php?search=${encodeURIComponent(query)}` : `/api/api_users.php?search=${encodeURIComponent(query)}`;
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
                        searchResults.innerHTML = `<p>No ${type} found.</p>`;
                    }
                } catch (error) {
                    console.error(`Error fetching search results for ${type}:`, error);
                    searchResults.innerHTML = `<p>Error loading search results. Please try again later.</p>`;
                }
            }
        });
    }

    // Load default results (services)
    loadSearchResults("services");

});