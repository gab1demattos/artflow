document.addEventListener("DOMContentLoaded", () => {
	const searchIcon = document.getElementById("search-icon");
	const SearchInputPage = document.getElementById("search-page-input");
	const SearchBarPage = document.getElementById("search-page-bar");
	const searchServicesBtn = document.getElementById("search-services");
	const searchNamesBtn = document.getElementById("search-names");
	const searchResults = document.getElementById("search-results");
	const filterSearch = document.getElementById("filter-search");
	const deliveryTimeInput = document.getElementById("delivery-time");
	const filterRatingStars = document.querySelectorAll(
		"#filter-search-rating .star-icon"
	);
	const filterRatingValue = document.getElementById("filter-rating-value");
	const filterRatingText = document.getElementById("filter-rating-text");

	const minPriceInput = document.querySelector(".min-price");
	const maxPriceInput = document.querySelector(".max-price");
	const minPriceDisplay = document.getElementById("min-value");
	const maxPriceDisplay = document.getElementById("max-value");

	const categoryCheckboxes = document.querySelectorAll(
		'.filter-option-category input[type="checkbox"]'
	);

	let currentFilterRating = 0;

	if (searchIcon) {
		searchIcon.addEventListener("click", () => {
			window.location.href = "/pages/services/search.php";
		});
	}

	if (window.location.pathname.includes("search.php")) {
		initial("services"); 
	}

	function updateFilterStarDisplay(rating) {
		if (filterRatingStars.length === 0) return;

		filterRatingStars.forEach((star) => {
			const starValue = parseFloat(star.getAttribute("data-value"));

			if (rating >= starValue) {
				star.textContent = "★";
				star.classList.add("active");
				star.classList.remove("half");
			}
			else if (rating === starValue - 0.5) {
				star.textContent = "★";
				star.classList.add("active", "half");
			}
			else {
				star.textContent = "★";
				star.classList.remove("active", "half");
			}
		});

		if (filterRatingValue) {
			filterRatingValue.value = rating;
		}
		if (filterRatingText) {
			filterRatingText.textContent = rating.toFixed(1);
		}
	}

	function updatePriceRange() {
		if (!minPriceInput || !maxPriceInput || !minPriceDisplay || !maxPriceDisplay) return;

		let minPrice = parseFloat(minPriceInput.value);
		let maxPrice = parseFloat(maxPriceInput.value);

		if (minPrice > maxPrice) {
			minPrice = maxPrice;
			minPriceInput.value = minPrice;
		}

		minPriceDisplay.textContent = minPrice;
		maxPriceDisplay.textContent = maxPrice;
	}

	async function fetchFilteredServices() {
		const selectedCategories = Array.from(
			document.querySelectorAll(
				'.filter-option-category input[type="checkbox"]:checked'
			)
		).map((cb) => cb.id.replace("filter-option-", ""));

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

		console.log("Fetching services with filters:", {
			categories: selectedCategories,
			minPrice,
			maxPrice,
			maxDeliveryTime,
			minRating,
		});

		let apiUrl = `/api/api_services.php?categories=${selectedCategories.join(",")}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}&min_rating=${minRating}`;

		console.log("API URL:", apiUrl);

		try {
			const response = await fetch(apiUrl);

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			const services = await response.json();

			console.log("API Response:", services);
			console.log("Number of services returned:", services.length);

			if (!searchResults) return;

			searchResults.innerHTML = ""; 

			if (services.length > 0) {
				services.forEach((service) => {
					const serviceCard = document.createElement("a");
					serviceCard.href = `/pages/services/service.php?id=${encodeURIComponent(
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
                                <span class="pantone-username">${service.username}</span>
                                <span class="pantone-rating">★ ${service.rating || "0.0"}</span>
                                <span class="pantone-delivery-time">${service.price}€</span>
                            </div>
                        </div>
                    `;
					searchResults.appendChild(serviceCard);
				});
			} else {
				searchResults.innerHTML = "<p>No services found for the selected filters.</p>";
			}
		} catch (error) {
			console.error("Error fetching services:", error);
			if (searchResults) {
				searchResults.innerHTML = "<p>Error loading services. Please try again later.</p>";
			}
		}
	}

	if (searchServicesBtn) {
		searchServicesBtn.addEventListener("click", () => {
			searchServicesBtn.classList.add("active");
			if (searchNamesBtn) searchNamesBtn.classList.remove("active");
			if (searchResults) {
				searchResults.classList.add("services-active");
				searchResults.classList.remove("names-active");
			}

			if (SearchInputPage) SearchInputPage.value = "";

			const filterInputs = document.querySelectorAll(
				'.filter-option-category input[type="checkbox"], #min-price, #max-price, #delivery-time'
			);
			filterInputs.forEach((input) => {
				if (input.type === "checkbox") {
					input.checked = true;
				} else if (input.id === "min-price" || input.id === "max-price") {
					input.value = input.id === "min-price" ? input.min : input.max;
				} else if (input.id === "delivery-time") {
					input.value = input.max;
				}
			});

			currentFilterRating = 0;
			updateFilterStarDisplay(0);

			if (minPriceInput) {
				minPriceInput.value = minPriceInput.min;
			}

			if (maxPriceInput) {
				maxPriceInput.value = maxPriceInput.max;
			}

			if (minPriceDisplay && minPriceInput) {
				minPriceDisplay.textContent = minPriceInput.min;
			}

			if (maxPriceDisplay && maxPriceInput) {
				maxPriceDisplay.textContent = maxPriceInput.max;
			}

			updatePriceRange();

			if (filterSearch) filterSearch.classList.remove("hidden");

			loadSearchResults("services", SearchInputPage);
			
			initial("services");
		});
	}

	if (searchNamesBtn) {
		searchNamesBtn.addEventListener("click", () => {
			searchNamesBtn.classList.add("active");
			if (searchServicesBtn) searchServicesBtn.classList.remove("active");
			if (searchResults) {
				searchResults.classList.add("names-active");
				searchResults.classList.remove("services-active");
			}

			if (SearchInputPage) SearchInputPage.value = "";

			const filterInputs = document.querySelectorAll(
				'.filter-option-category input[type="checkbox"], #min-price, #max-price'
			);
			filterInputs.forEach((input) => {
				if (input.type === "checkbox") {
					input.checked = true;
				} else {
					input.value = input.id === "min-price" ? "0" : "1000";
				}
			});

			if (filterSearch) filterSearch.classList.add("hidden");
			if (SearchBarPage) SearchBarPage.classList.add("names-active");

			loadSearchResults("names", SearchInputPage);
			
			initial("names");
		});
	}

	function loadSearchResults(type, inputElement) {
		if (!inputElement) return;

		const listener = async function () {
			if (!searchResults) return;

			const query = inputElement.value.trim();

			if (type === "services") {
				if (query === "") {
					fetchFilteredServices();
				} else {
					const selectedCategories = Array.from(
						document.querySelectorAll(
							'.filter-option-category input[type="checkbox"]:checked'
						)
					).map((cb) => cb.id.replace("filter-option-", ""));

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

					let apiUrl = `/api/api_services.php?search=${encodeURIComponent(query)}&categories=${selectedCategories.join(",")}&min_price=${minPrice}&max_price=${maxPrice}&max_delivery_time=${maxDeliveryTime}&min_rating=${minRating}`;

					try {
						const response = await fetch(apiUrl);
						if (!response.ok) {
							throw new Error(`HTTP error! status: ${response.status}`);
						}
						
						const services = await response.json();

						searchResults.innerHTML = ""; 

						if (services.length > 0) {
							services.forEach((service) => {
								const serviceCard = document.createElement("a");
								serviceCard.href = `/pages/services/service.php?id=${encodeURIComponent(service.id)}`;
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
											<span class="pantone-username">${service.username}</span>
											<span class="pantone-rating">★ ${service.rating || "0.0"}</span>
											<span class="pantone-delivery-time">${service.price}€</span>
										</div>
									</div>
								`;
								searchResults.appendChild(serviceCard);
							});
						} else {
							searchResults.innerHTML = "<p>No services found for the search term with current filters.</p>";
						}
					} catch (error) {
						console.error("Error fetching filtered search results:", error);
						searchResults.innerHTML = "<p>Error loading search results. Please try again later.</p>";
					}
				}
			} else {
				searchResults.innerHTML = ""; 

				const endpoint = query === ""
					? "/api/api_users.php"
					: `/api/api_users.php?search=${encodeURIComponent(query)}`;

				try {
					const response = await fetch(endpoint);
					const data = await response.json();

					searchResults.innerHTML = ""; 
					if (data.length > 0) {
						data.forEach((item) => {
							const card = document.createElement("a");
							card.href = `/pages/users/profile.php?username=${encodeURIComponent(item.username)}`;
							card.classList.add("user-card-link");
							card.innerHTML = `
								<div class="user-card">
									<div class="user-info">
										<img src="${item.profilePicture || "/images/user_pfp/default.png"}" alt="User profile picture" class="user-profile-picture" />
										<p class="user-name">${item.name}</p>
										<p class="user-username">@${item.username}</p>
									</div>
								</div>
							`;
							searchResults.appendChild(card);
						});
					} else {
						searchResults.innerHTML = `<p>No users found.</p>`;
					}
				} catch (error) {
					console.error(`Error fetching search results for users:`, error);
					searchResults.innerHTML = `<p>Error loading search results. Please try again later.</p>`;
				}
			}
		};

		if (inputElement._listener) {
			inputElement.removeEventListener("input", inputElement._listener);
		}

		inputElement._listener = listener;
		inputElement.addEventListener("input", listener);
	}

	function initial(type) {
		if (!searchResults) return;

		searchResults.innerHTML = "";

		if (type === "services") {
			fetch("/api/api_all_services.php")
				.then((response) => response.json())
				.then((services) => {
					searchResults.innerHTML = ""; 
					if (services.length > 0) {
						services.forEach((service) => {
							const serviceCard = document.createElement("a");
							serviceCard.href = `/pages/services/service.php?id=${encodeURIComponent(service.id)}`;
							serviceCard.classList.add("service-card-link");
							serviceCard.innerHTML = `
                            <div class="service-card" data-subcategory-ids="${encodeURIComponent(service.subcategories || "")}">
                                <div class="pantone-image-wrapper">
                                    ${
										service.image
											? `<img src="${service.image}" alt="Service image" class="pantone-image" />`
											: '<div class="pantone-image pantone-image-placeholder"></div>'
									}
                                </div>
                                <div class="pantone-title">${service.title}</div>
                                <div class="pantone-info-row">
                                    <span class="pantone-username">${service.username}</span>
                                    <span class="pantone-rating">★ ${service.rating || "0.0"}</span>
                                    <span class="pantone-delivery-time">${service.price}€</span>
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
					searchResults.innerHTML = "<p>Error loading services. Please try again later.</p>";
				});
		} else if (type === "names") {
			fetch("/api/api_users.php")
				.then((response) => response.json())
				.then((users) => {
					searchResults.innerHTML = ""; 
					if (users.length > 0) {
						users.forEach((user) => {
							const userCard = document.createElement("a");
							userCard.href = `/pages/users/profile.php?username=${encodeURIComponent(user.username)}`;
							userCard.classList.add("user-card-link");
							userCard.innerHTML = `
                            <div class="user-card">
                                <div class="user-info">
                                    <img src="${user.profilePicture || "/images/user_pfp/default.png"}" alt="User profile picture" class="user-profile-picture" />
                                    <p class="user-name">${user.name}</p>
                                    <p class="user-username">@${user.username}</p>
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
					searchResults.innerHTML = "<p>Error loading users. Please try again later.</p>";
				});
		}
	}

	if (SearchInputPage) {
		loadSearchResults("services", SearchInputPage);
	}

	categoryCheckboxes.forEach((checkbox) => {
		checkbox.addEventListener("change", fetchFilteredServices);
	});

	filterRatingStars.forEach((star) => {
		star.addEventListener("mousemove", function (e) {
			const rect = this.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const starValue = parseFloat(this.getAttribute("data-value"));

			if (x < rect.width / 2) {
				updateFilterStarDisplay(starValue - 0.5);
			} else {
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
			fetchFilteredServices(); 
		});
	});

	const filterStarsContainer = document.querySelector("#filter-search-rating .stars-container");
	if (filterStarsContainer) {
		filterStarsContainer.addEventListener("mouseleave", function () {
			updateFilterStarDisplay(currentFilterRating);
		});
	}

	if (minPriceInput) {
		minPriceInput.addEventListener("input", () => {
			if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
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
			if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
				maxPriceInput.value = minPriceInput.value;
			}
			if (maxPriceDisplay) {
				maxPriceDisplay.textContent = maxPriceInput.value;
			}
			fetchFilteredServices();
		});
	}

	if (deliveryTimeInput) {
		deliveryTimeInput.addEventListener("change", fetchFilteredServices);
	}

	const clearRatingBtn = document.getElementById("clear-rating");
	if (clearRatingBtn) {
		clearRatingBtn.addEventListener("click", () => {
			currentFilterRating = 0;
			updateFilterStarDisplay(0);
			fetchFilteredServices(); 
		});
	}
});