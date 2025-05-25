document.addEventListener("DOMContentLoaded", () => {
	const searchInput = document.getElementById("search-input");
	const searchButton = document.getElementById("search-button");
	const SearchInputPage = document.getElementById("search-page-input");
	const SearchBarPage = document.getElementById("search-page-bar");
	const searchServicesBtn = document.getElementById("search-services");
	const searchNamesBtn = document.getElementById("search-names");
	const searchResults = document.getElementById("search-results");
	const searchBar = document.getElementById("search-bar");
	const filterSearch = document.getElementById("filter-search");
	const deliveryTimeInput = document.getElementById("delivery-time");
	const filterRatingStars = document.querySelectorAll(
		"#filter-search-rating .star-icon"
	);
	const filterRatingValue = document.getElementById("filter-rating-value");
	const filterRatingText = document.getElementById("filter-rating-text");

	// Single rating variable declaration
	let currentFilterRating = 0;

	// Get price range inputs
	const minPriceInput = document.querySelector(".min-price");
	const maxPriceInput = document.querySelector(".max-price");
	const minPriceDisplay = document.getElementById("min-value");
	const maxPriceDisplay = document.getElementById("max-value");

	// Redirect to search.php when the search bar is clicked
	if (searchBar) {
		searchBar.addEventListener("click", () => {
			window.location.href = "../pages/search.php";
			searchBar.classList.add("hidden");
		});
	}

	// Call initial() when the search.php page loads
	if (window.location.pathname.includes("search.php")) {
		initial("services"); // Default to "services" on page load
	}

	// Function to update star display for filters
	function updateFilterStarDisplay(rating) {
		filterRatingStars.forEach((star) => {
			const starValue = parseFloat(star.getAttribute("data-value"));

			// Full star
			if (rating >= starValue) {
				star.textContent = "★";
				star.classList.add("active");
				star.classList.remove("half");
			}
			// Half star
			else if (rating === starValue - 0.5) {
				star.textContent = "★";
				star.classList.add("active", "half");
			}
			// Empty star
			else {
				star.textContent = "★";
				star.classList.remove("active", "half");
			}
		});

		// Update the rating text and hidden value
		if (filterRatingValue) {
			filterRatingValue.value = rating;
		}
		if (filterRatingText) {
			filterRatingText.textContent = rating.toFixed(1);
		}
	}

	// Single consolidated function to fetch filtered services
	async function fetchFilteredServices() {
		const selectedCategories = Array.from(
			document.querySelectorAll(
				'.filter-option-category input[type="checkbox"]:checked'
			)
		).map((cb) => cb.id.replace("filter-option-", ""));

		// Use safe defaults and check if elements exist
		let minPrice = 0;
		let maxPrice = 1000;
		let maxDeliveryTime = 30;
		let minRating = currentFilterRating;

		if (minPriceInput) {
			minPrice = parseFloat(minPriceInput.value) || 0;
		}

		if (maxPriceInput) {
			maxPrice = parseFloat(maxPriceInput.value) || 1000;
		}

		if (deliveryTimeInput) {
			maxDeliveryTime = parseFloat(deliveryTimeInput.value) || 30;
		}

		// Build the API URL
		let apiUrl = `/api/api_services.php?categories=${selectedCategories.join(",")}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}&min_rating=${minRating}`;

		try {
			const response = await fetch(apiUrl);
			
			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}
			
			const services = await response.json();

			const searchResults = document.getElementById("search-results");
			searchResults.innerHTML = ""; // Clear previous results

			if (services.length > 0) {
				services.forEach((service) => {
					const serviceCard = document.createElement("a");
					serviceCard.href = `/pages/service.php?id=${encodeURIComponent(
						service.id
					)}`;
					serviceCard.classList.add("service-card-link");
					serviceCard.innerHTML = `
                        <div class="service-card">
                            <div class="pantone-image-wrapper">
                                ${
									service.image
										? `<img src="${service.image}" alt="Service image" class="pantone-image" />`
										: '<div class="pantone-image pantone-image-placeholder"></div>'
								}
                            </div>
                            <div class="pantone-title">${service.title}</div>
                            <div class="pantone-info-row">
                                <span class="pantone-username">${
									service.username
								}</span>
                                <span class="pantone-rating">★ ${
									service.rating || "0.0"
								}</span>
                                <span class="pantone-delivery-time">${
									service.price
								}€</span>
                            </div>
                        </div>
                    `;
					searchResults.appendChild(serviceCard);
				});
			} else {
				searchResults.innerHTML =
					"<p>No services found for the selected filters.</p>";
			}
		} catch (error) {
			console.error("Error fetching services:", error);
			searchResults.innerHTML =
				"<p>Error loading services. Please try again later.</p>";
		}
	}

	// Interrupt event listener when switching between buttons
	if (searchServicesBtn) {
		searchServicesBtn.addEventListener("click", () => {
			searchServicesBtn.classList.add("active");
			searchNamesBtn.classList.remove("active");
			searchResults.classList.add("services-active");
			searchResults.classList.remove("names-active");

			// Clear the search input when switching buttons
			if (SearchInputPage) SearchInputPage.value = "";

			// Reset filters when switching buttons
			const filterInputs = document.querySelectorAll(
				'.filter-option-category input[type="checkbox"], #min-price, #max-price, #delivery-time'
			);
			filterInputs.forEach((input) => {
				if (input.type === "checkbox") {
					input.checked = true; // Set all checkboxes to checked
				} else if (input.id === "min-price" || input.id === "max-price") {
					input.value = input.id === "min-price" ? input.min : input.max;
				} else if (input.id === "delivery-time") {
					input.value = input.max;
				}
			});

			// Reset star rating
			currentFilterRating = 0;
			updateFilterStarDisplay(0);

			// Reset price range inputs to their initial values
			if (minPriceInput) {
				minPriceInput.value = minPriceInput.min;
				if (minPriceDisplay) minPriceDisplay.textContent = minPriceInput.min;
			}

			if (maxPriceInput) {
				maxPriceInput.value = maxPriceInput.max;
				if (maxPriceDisplay) maxPriceDisplay.textContent = maxPriceInput.max;
			}

			// Show filter-search when services are active
			if (filterSearch) filterSearch.classList.remove("hidden");

			// Load search results for services
			loadSearchResults("services", SearchInputPage);
		});
	}

	if (searchNamesBtn) {
		searchNamesBtn.addEventListener("click", () => {
			searchNamesBtn.classList.add("active");
			searchServicesBtn.classList.remove("active");
			searchResults.classList.add("names-active");
			searchResults.classList.remove("services-active");

			// Clear the search input when switching buttons
			if (SearchInputPage) SearchInputPage.value = "";

			// Hide filter-search when names are active
			if (filterSearch) filterSearch.classList.add("hidden");
			if (SearchBarPage) SearchBarPage.classList.add("names-active");

			// Load search results for names
			loadSearchResults("names", SearchInputPage);
		});
	}

	const handleSearchBarInteraction = () => {
		// Determine which button is active and call initial with the appropriate type
		if (searchServicesBtn && searchServicesBtn.classList.contains("active")) {
			initial("services");
		} else if (searchNamesBtn && searchNamesBtn.classList.contains("active")) {
			initial("names");
		}
	};

	if (searchInput) {
		searchInput.addEventListener("focus", handleSearchBarInteraction);
		searchInput.addEventListener("blur", () => {
			if (searchButton) searchButton.style.display = "block";
		});
	}

	// Function to load search results dynamically
	function loadSearchResults(type, inputElement) {
		if (!inputElement) return;

		// Remove existing listener if it exists
		if (inputElement._listener) {
			inputElement.removeEventListener("input", inputElement._listener);
		}

		const listener = async function () {
			searchResults.innerHTML = ""; // Clear previous results
			const query = inputElement.value.trim();

			const endpoint =
				type === "services"
					? query === ""
						? "/api/api_all_services.php"
						: `/api/api_services.php?search=${encodeURIComponent(query)}`
					: query === ""
					? "/api/api_users.php"
					: `/api/api_users.php?search=${encodeURIComponent(query)}`;

			try {
				const response = await fetch(endpoint);
				const data = await response.json();

				searchResults.innerHTML = ""; // Clear previous results
				if (data.length > 0) {
					data.forEach((item) => {
						const card = document.createElement("a");
						if (type === "services") {
							card.href = `/pages/service.php?id=${encodeURIComponent(
								item.id
							)}`;
							card.classList.add("service-card-link");
							card.innerHTML = `
                                <div class="service-card">
                                    <div class="pantone-image-wrapper">
                                        ${
											item.image
												? `<img src="${item.image}" alt="Service image" class="pantone-image" />`
												: '<div class="pantone-image pantone-image-placeholder"></div>'
										}
                                    </div>
                                    <div class="pantone-title">${
										item.title
									}</div>
                                    <div class="pantone-info-row">
                                        <span class="pantone-username">${
											item.username
										}</span>
                                        <span class="pantone-rating">★ ${
											item.rating || "0.0"
										}</span>
                                        <span class="pantone-delivery-time">${
											item.price
										}€</span>
                                    </div>
                                </div>
                            `;
						} else {
							card.href = `/pages/profile.php?username=${encodeURIComponent(
								item.username
							)}`;
							card.classList.add("user-card-link");
							card.innerHTML = `
                                <div class="user-card">
                                    <div class="user-info">
                                        <img src="${
											item.profilePicture ||
											"/images/user_pfp/default.png"
										}" alt="User profile picture" class="user-profile-picture" />
                                        <p class="user-name">${item.name}</p>
                                        <p class="user-username">@${
											item.username
										}</p>
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
		};

		inputElement._listener = listener;
		inputElement.addEventListener("input", listener);
	}

	function initial(type) {
		if (!searchResults) return;
		
		searchResults.innerHTML = ""; // Clear previous results

		if (type === "services") {
			// Make an AJAX request to fetch all services
			fetch("/api/api_all_services.php")
				.then((response) => response.json())
				.then((services) => {
					searchResults.innerHTML = ""; // Clear previous results
					if (services.length > 0) {
						services.forEach((service) => {
							const serviceCard = document.createElement("a");
							serviceCard.href = `/pages/service.php?id=${encodeURIComponent(
								service.id
							)}`;
							serviceCard.classList.add("service-card-link");
							serviceCard.innerHTML = `
                            <div class="service-card" data-subcategory-ids="${encodeURIComponent(
								service.subcatIdsStr || ""
							)}">
                                <div class="pantone-image-wrapper">
                                    ${
										service.image
											? `<img src="${service.image}" alt="Service image" class="pantone-image" />`
											: '<div class="pantone-image pantone-image-placeholder"></div>'
									}
                                </div>
                                <div class="pantone-title">${
									service.title
								}</div>
                                <div class="pantone-info-row">
                                    <span class="pantone-username">${
										service.username
									}</span>
                                    <span class="pantone-rating">★ ${
										service.rating || "0.0"
									}</span>
                                    <span class="pantone-delivery-time">${
										service.price
									}€</span>
                                </div>
                            </div>
                        `;
							searchResults.appendChild(serviceCard);
						});
					} else {
						searchResults.innerHTML = "<p>No services found.</p>";
					}
				})
				.catch((error) => {
					console.error("Error fetching services:", error);
					searchResults.innerHTML =
						"<p>Error loading services. Please try again later.</p>";
				});
		} else if (type === "names") {
			// Make an AJAX request to fetch all users
			fetch("/api/api_users.php")
				.then((response) => response.json())
				.then((users) => {
					searchResults.innerHTML = ""; // Clear previous results
					if (users.length > 0) {
						users.forEach((user) => {
							const userCard = document.createElement("a");
							userCard.href = `/pages/profile.php?username=${encodeURIComponent(
								user.username
							)}`;
							userCard.classList.add("user-card-link");
							userCard.innerHTML = `
                            <div class="user-card">
                                <div class="user-info">
                                    <img src="${
										user.profilePicture ||
										"/images/user_pfp/default.png"
									}" alt="User profile picture" class="user-profile-picture" />
                                    <p class="user-username">${user.name}</p>
                                    <p class="user-username">@${
										user.username
									}</p>
                                </div>
                            </div>
                        `;
							searchResults.appendChild(userCard);
						});
					} else {
						searchResults.innerHTML = "<p>No users found.</p>";
					}
				})
				.catch((error) => {
					console.error("Error fetching users:", error);
					searchResults.innerHTML =
						"<p>Error loading users. Please try again later.</p>";
				});
		}
	}

	// Load default results (services)
	if (SearchInputPage) {
		loadSearchResults("services", SearchInputPage);
	}

	// Event listeners for filters
	const categoryCheckboxes = document.querySelectorAll(
		'.filter-option-category input[type="checkbox"]'
	);

	categoryCheckboxes.forEach((checkbox) => {
		checkbox.addEventListener("change", fetchFilteredServices);
	});

	// Star rating event handling
	filterRatingStars.forEach((star) => {
		star.addEventListener("mousemove", function (e) {
			const rect = this.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const starValue = parseFloat(this.getAttribute("data-value"));

			if (x < rect.width / 2) {
				// Left half of star - show half star rating
				updateFilterStarDisplay(starValue - 0.5);
			} else {
				// Right half of star - show full star rating
				updateFilterStarDisplay(starValue);
			}
		});

		star.addEventListener("click", function (e) {
			const rect = this.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const starValue = parseFloat(this.getAttribute("data-value"));

			if (x < rect.width / 2) {
				currentFilterRating = starValue - 0.5;
			} else {
				currentFilterRating = starValue;
			}

			updateFilterStarDisplay(currentFilterRating);
			fetchFilteredServices(); // Trigger search update with new rating filter
		});
	});

	// Reset to current rating when mouse leaves container
	const filterStarsContainer = document.querySelector(
		"#filter-search-rating .stars-container"
	);
	if (filterStarsContainer) {
		filterStarsContainer.addEventListener("mouseleave", function () {
			updateFilterStarDisplay(currentFilterRating);
		});
	}

	// Price range event listeners
	if (minPriceInput) {
		minPriceInput.addEventListener("input", () => {
			if (maxPriceInput && parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
				minPriceInput.value = maxPriceInput.value;
			}
			if (minPriceDisplay) {
				minPriceDisplay.textContent = minPriceInput.value;
			}
			fetchFilteredServices();
		});
	}

	if (maxPriceInput) {
		maxPriceInput.addEventListener("input", () => {
			if (minPriceInput && parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
				maxPriceInput.value = minPriceInput.value;
			}
			if (maxPriceDisplay) {
				maxPriceDisplay.textContent = maxPriceInput.value;
			}
			fetchFilteredServices();
		});
	}

	// Delivery time event listener
	if (deliveryTimeInput) {
		deliveryTimeInput.addEventListener("change", fetchFilteredServices);
	}

	// Add clear rating button functionality
	const clearRatingBtn = document.getElementById("clear-rating");
	if (clearRatingBtn) {
		clearRatingBtn.addEventListener("click", () => {
			currentFilterRating = 0;
			updateFilterStarDisplay(0);
			fetchFilteredServices(); // Update results
		});
	}
});