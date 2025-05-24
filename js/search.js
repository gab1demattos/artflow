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
	window.currentFilterRating = 0; // Using window to share state

	// Redirect to search.php when the search bar is clicked
	searchBar.addEventListener("click", () => {
		window.location.href = "../pages/search.php";
		searchBar.classList.add("hidden"); // Use a CSS class to hide the search bar
	});

	// Call initial() when the search.php page loads
	if (window.location.pathname.includes("search.php")) {
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
		if (window.filterRating) {
			window.filterRating.setRating(0);
		}

		// Reset price range inputs to their initial values - check if they exist first
		if (typeof minPriceInput !== "undefined" && minPriceInput) {
			minPriceInput.value = minPriceInput.min;
		}

		if (typeof maxPriceInput !== "undefined" && maxPriceInput) {
			maxPriceInput.value = maxPriceInput.max;
		}

		// Update the displayed values - check if they exist first
		if (
			typeof minPriceDisplay !== "undefined" &&
			minPriceDisplay &&
			typeof minPriceInput !== "undefined" &&
			minPriceInput
		) {
			minPriceDisplay.textContent = minPriceInput.min;
		}

		if (
			typeof maxPriceDisplay !== "undefined" &&
			maxPriceDisplay &&
			typeof maxPriceInput !== "undefined" &&
			maxPriceInput
		) {
			maxPriceDisplay.textContent = maxPriceInput.max;
		}

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
		const filterInputs = document.querySelectorAll(
			'.filter-option-category input[type="checkbox"], #min-price, #max-price'
		);
		filterInputs.forEach((input) => {
			if (input.type === "checkbox") {
				input.checked = true; // Set all checkboxes to checked
			} else {
				input.value = input.id === "min-price" ? "0" : "1000"; // Set price inputs to initial values
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

	searchInput.addEventListener("focus", handleSearchBarInteraction);
	searchServicesBtn.addEventListener("click", handleSearchBarInteraction);
	searchNamesBtn.addEventListener("click", handleSearchBarInteraction);

	searchInput.addEventListener("blur", () => {
		searchButton.style.display = "block";
	});

	// Function to load search results dynamically
	function loadSearchResults(type, inputElement) {
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
																					service.price
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
		inputElement.addEventListener("input", listener);
	}

	function initial(type) {
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
						searchResults.innerHTML = ""; // Clear previous results
						searchResults.innerHTML = "<p>No services found.</p>";
					}
				})
				.catch((error) => {
					console.error("Error fetching services:", error);
					searchResults.innerHTML = ""; // Clear previous results
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
						searchResults.innerHTML = ""; // Clear previous results
						searchResults.innerHTML = "<p>No users found.</p>";
					}
				})
				.catch((error) => {
					console.error("Error fetching users:", error);
					searchResults.innerHTML = ""; // Clear previous results
					searchResults.innerHTML =
						"<p>Error loading users. Please try again later.</p>";
				});
		}
	}

	// Load default results (services)
	loadSearchResults("services", SearchInputPage);

	const categoryCheckboxes = document.querySelectorAll(
		'.filter-option-category input[type="checkbox"]'
	);

	categoryCheckboxes.forEach((checkbox) => {
		checkbox.addEventListener("change", async () => {
			const selectedCategories = Array.from(categoryCheckboxes)
				.filter((cb) => cb.checked)
				.map((cb) => cb.id.replace("filter-option-", ""));

			console.log("Selected Categories:", selectedCategories);

			// Fetch services dynamically based on selected categories
			try {
				const response = await fetch(
					`/api/api_services.php?categories=${selectedCategories.join(",")}`
				);
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
					searchResults.innerHTML = ""; // Clear previous results
					searchResults.innerHTML =
						"<p>No services found for the selected categories.</p>";
				}
			} catch (error) {
				console.error("Error fetching services:", error);
				searchResults.innerHTML = ""; // Clear previous results
				searchResults.innerHTML =
					"<p>Error loading services. Please try again later.</p>";
			}
		});
	});

	// Add event listeners for price range inputs
	const minPriceInput = document.querySelector(".min-price");
	const maxPriceInput = document.querySelector(".max-price");
	const minPriceDisplay = document.getElementById("min-value");
	const maxPriceDisplay = document.getElementById("max-value");

	const updatePriceRange = async () => {
		let minPrice = parseFloat(minPriceInput.value);
		let maxPrice = parseFloat(maxPriceInput.value);

		if (minPrice > maxPrice) {
			minPrice = maxPrice;
			minPriceInput.value = minPrice;
		}

		minPriceDisplay.textContent = minPrice;
		maxPriceDisplay.textContent = maxPrice;

		const selectedCategories = Array.from(
			document.querySelectorAll(
				'.filter-option-category input[type="checkbox"]'
			)
		)
			.filter((cb) => cb.checked)
			.map((cb) => cb.id.replace("filter-option-", ""));

		console.log("Selected Categories:", selectedCategories);
		console.log("Price Range:", minPrice, maxPrice);

		try {
			const response = await fetch(
				`/api/api_services.php?categories=${selectedCategories.join(
					","
				)}&min_price=${minPrice}&max_price=${maxPrice}`
			);
			const services = await response.json();

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
	};

	minPriceInput.addEventListener("input", () => {
		if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
			minPriceInput.value = maxPriceInput.value;
		}
		updatePriceRange();
	});

	maxPriceInput.addEventListener("input", () => {
		console.log("Max Price Input Changed:", maxPriceInput.value); // Debugging log
		if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
			maxPriceInput.value = minPriceInput.value;
		}
		maxPriceDisplay.textContent = maxPriceInput.value; // Ensure maxPriceDisplay is updated
		updatePriceRange();
	});

	deliveryTimeInput.addEventListener("change", async () => {
		const maxDeliveryTime = parseFloat(deliveryTimeInput.value);

		const selectedCategories = Array.from(categoryCheckboxes)
			.filter((cb) => cb.checked)
			.map((cb) => cb.id.replace("filter-option-", ""));

		const minPrice = parseFloat(minPriceInput.value);
		const maxPrice = parseFloat(maxPriceInput.value);

		console.log("Selected Categories:", selectedCategories);
		console.log("Price Range:", minPrice, maxPrice);
		console.log("Max Delivery Time:", maxDeliveryTime);

		// Fetch services dynamically based on selected categories, price range, and delivery time
		try {
			const response = await fetch(
				`/api/api_services.php?categories=${selectedCategories.join(
					","
				)}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}`
			);
			const services = await response.json();

			console.log("Fetched Services:", services); // Debugging log

			searchResults.innerHTML = ""; // Clear previous results

			if (services.length > 0) {
				services.forEach((service) => {
					if (service.delivery_time <= maxDeliveryTime) {
						// Ensure filtering is applied
						console.log("Service Passed Filter:", service); // Debugging log
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
					} else {
						console.log("Service Failed Filter:", service); // Debugging log
					}
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
	});

	// Initialize star rating filter functionality
	// Function to update star display for filters
	function updateFilterStarDisplay(rating) {
		filterRatingStars.forEach((star) => {
			const starValue = parseFloat(star.getAttribute("data-value"));
			const starIndex = Math.ceil(starValue) - 1;

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

		filterRatingValue.value = rating;
		filterRatingText.textContent = rating.toFixed(1);
	}

	// Handle star hover and click events for filters
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
				window.currentFilterRating = starValue - 0.5;
			} else {
				window.currentFilterRating = starValue;
			}

			updateFilterStarDisplay(window.currentFilterRating);
			fetchFilteredServices(); // Trigger search update with new rating filter
		});
	});

	// Reset to current rating when mouse leaves container
	const filterStarsContainer = document.querySelector(
		"#filter-search-rating .stars-container"
	);
	if (filterStarsContainer) {
		filterStarsContainer.addEventListener("mouseleave", function () {
			updateFilterStarDisplay(window.currentFilterRating);
		});
	}

	// Modified fetchFilteredServices function to include rating filter
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
		let minRating = 0;

		const minPriceElement = document.querySelector(".min-price");
		const maxPriceElement = document.querySelector(".max-price");
		const deliveryTimeElement = document.getElementById("delivery-time");

		if (minPriceElement) {
			minPrice = parseFloat(minPriceElement.value) || 0;
		}

		if (maxPriceElement) {
			maxPrice = parseFloat(maxPriceElement.value) || 1000;
		}

		if (deliveryTimeElement) {
			maxDeliveryTime = parseFloat(deliveryTimeElement.value) || 30;
		}

		if (filterRatingValue) {
			minRating = parseFloat(filterRatingValue.value) || 0;
		}

		console.log("Fetching services with filters:", {
			categories: selectedCategories,
			minPrice,
			maxPrice,
			maxDeliveryTime,
			minRating,
		});

		try {
			const response = await fetch(
				`/api/api_services.php?categories=${selectedCategories.join(
					","
				)}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}&min_rating=${minRating}`
			);
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

	// Add event listeners for filter changes
	document
		.querySelectorAll('.filter-option-category input[type="checkbox"]')
		.forEach((checkbox) => {
			checkbox.addEventListener("change", fetchFilteredServices);
		});

	minPriceInput.addEventListener("input", () => {
		if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
			minPriceInput.value = maxPriceInput.value;
		}
		fetchFilteredServices();
	});

	maxPriceInput.addEventListener("input", () => {
		if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
			maxPriceInput.value = minPriceInput.value;
		}
		maxPriceDisplay.textContent = maxPriceInput.value;
		fetchFilteredServices();
	});

	deliveryTimeInput.addEventListener("change", fetchFilteredServices);

	// Add clear rating button functionality
	const clearRatingBtn = document.getElementById("clear-rating");
	if (clearRatingBtn) {
		clearRatingBtn.addEventListener("click", () => {
			window.currentFilterRating = 0;
			updateFilterStarDisplay(0);
			fetchFilteredServices(); // Update results
		});
	}

	// End of the file
	let currentFilterRating = 0;

	// Function to update filter stars display
	function updateFilterStarDisplay(rating) {
		filterRatingStars.forEach((star) => {
			const starValue = parseFloat(star.getAttribute("data-value"));
			const starIndex = Math.ceil(starValue) - 1;

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
		filterRatingText.textContent = rating.toFixed(1);
		filterRatingValue.value = rating;
	}

	// Handle star hover for preview
	filterRatingStars.forEach((star) => {
		star.addEventListener("mousemove", function (e) {
			const rect = this.getBoundingClientRect();
			const x = e.clientX - rect.left; // x position within the star
			const starValue = parseFloat(this.getAttribute("data-value"));

			if (x < rect.width / 2) {
				// Left half of the star - show half star
				updateFilterStarDisplay(starValue - 0.5);
			} else {
				// Right half of the star - show full star
				updateFilterStarDisplay(starValue);
			}
		});

		// Set rating on click
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
			updateSearchResults(); // Trigger search update with new rating filter
		});
	});

	// Reset to current rating when mouse leaves container
	const starsContainer = document.querySelector(
		"#filter-search-rating .stars-container"
	);
	if (starsContainer) {
		starsContainer.addEventListener("mouseleave", function () {
			updateFilterStarDisplay(currentFilterRating);
		});
	}

	// Modified updateSearchResults to include rating filter
	async function updateSearchResults() {
		const selectedCategories = Array.from(
			document.querySelectorAll(
				'.filter-option-category input[type="checkbox"]:checked'
			)
		).map((cb) => cb.id.replace("filter-option-", ""));

		const minPrice = parseFloat(document.querySelector(".min-price").value);
		const maxPrice = parseFloat(document.querySelector(".max-price").value);
		const maxDeliveryTime = parseFloat(
			document.getElementById("delivery-time").value
		);
		const minRating = currentFilterRating;

		try {
			const response = await fetch(
				`/api/api_services.php?categories=${selectedCategories.join(
					","
				)}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}&min_rating=${minRating}`
			);
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
});
