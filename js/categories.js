/**
 * Categories management
 * Handles category and subcategory related functionality
 */

const Categories = {
	// Keep track of current category selection
	currentCategoryId: null,
	selectedSubcatValues: [],

	/**
	 * Initialize category functionality
	 */
	init() {
		this.setupSubcategoryOverlay();
		this.setupSubcategoryTagFiltering();
		this.setupFilters(); // Added filters setup
	},

	/**
	 * Set up subcategory selection overlay for the new service form
	 */
	setupSubcategoryOverlay() {
		// Elements
		const openSubcatBtn = document.getElementById("open-subcategory-overlay");
		const categorySelect = document.getElementById("category-select");
		const subcategorySection = document.getElementById("subcategory-section");
		const subcategoryOverlay = document.getElementById("subcategory-overlay");
		const subcategoryModal = document.getElementById("subcategory-modal");
		const subcategoryForm = document.getElementById("subcategory-form");
		const subcategoryCheckboxesDiv = subcategoryModal
			? subcategoryModal.querySelector(".subcategory-checkboxes")
			: null;
		const closeSubcategoryBtns = document.querySelectorAll(
			".close-subcategory-modal"
		);

		// Category selection change handler
		if (
			categorySelect &&
			openSubcatBtn &&
			typeof subcategoriesByCategory !== "undefined"
		) {
			categorySelect.addEventListener("change", function () {
				const catId = this.value;
				Categories.currentCategoryId = catId;

				// Only show button if there are subcategories
				if (
					catId &&
					subcategoriesByCategory[catId] &&
					subcategoriesByCategory[catId].length > 0
				) {
					openSubcatBtn.style.display = "";
				} else {
					openSubcatBtn.style.display = "none";
					// Clear any previous summary
					if (subcategorySection) subcategorySection.innerHTML = "";
				}
			});

			// Open subcategory overlay when button is clicked
			openSubcatBtn.addEventListener("click", function () {
				if (
					!Categories.currentCategoryId ||
					!subcategoriesByCategory[Categories.currentCategoryId]
				)
					return;

				// Show overlay/modal for subcategory selection
				if (subcategoryOverlay && subcategoryCheckboxesDiv) {
					subcategoryCheckboxesDiv.innerHTML = "";
					subcategoriesByCategory[Categories.currentCategoryId].forEach(
						(subcat) => {
							const checkbox = document.createElement("input");
							checkbox.type = "checkbox";
							checkbox.name = "subcategories[]";
							checkbox.value = subcat.id;
							checkbox.id = "subcat-" + subcat.id;

							if (Categories.selectedSubcatValues.includes(subcat.id))
								checkbox.checked = true;

							const cbLabel = document.createElement("label");
							cbLabel.htmlFor = checkbox.id;
							cbLabel.textContent = subcat.name;

							// Insert checkbox before label for CSS sibling selector
							subcategoryCheckboxesDiv.appendChild(checkbox);
							subcategoryCheckboxesDiv.appendChild(cbLabel);

							// Add selected class for checked
							if (checkbox.checked) cbLabel.classList.add("selected");

							checkbox.addEventListener("change", function () {
								if (checkbox.checked) {
									cbLabel.classList.add("selected");
								} else {
									cbLabel.classList.remove("selected");
								}
							});
						}
					);

					subcategoryOverlay.classList.remove("hidden");
				}
			});
		}

		// Handle subcategory form submission
		if (subcategoryForm) {
			subcategoryForm.addEventListener("submit", function (e) {
				e.preventDefault();

				// Collect checked subcategories
				const checked = Array.from(
					subcategoryCheckboxesDiv.querySelectorAll(
						'input[type="checkbox"]:checked'
					)
				);
				Categories.selectedSubcatValues = checked.map((cb) => cb.value);

				// Show summary in main modal (optional)
				if (subcategorySection) {
					if (Categories.selectedSubcatValues.length > 0) {
						const summary = document.createElement("div");
						summary.className = "subcategory-summary";
						summary.textContent =
							"Selected: " +
							checked
								.map((cb) => cb.parentElement.textContent.trim())
								.join(", ");
						subcategorySection.innerHTML = "";
						subcategorySection.appendChild(summary);
					} else {
						subcategorySection.innerHTML = "";
					}
				}

				// Add hidden inputs for selected subcategories so they are submitted with the form
				const form = document.getElementById("new-service-form");
				if (form) {
					// Remove old hidden subcategory inputs
					Array.from(
						form.querySelectorAll(
							'input[type="hidden"][name="subcategories[]"]'
						)
					).forEach((el) => el.remove());

					// Add new ones
					Categories.selectedSubcatValues.forEach((val) => {
						const hidden = document.createElement("input");
						hidden.type = "hidden";
						hidden.name = "subcategories[]";
						hidden.value = val;
						form.appendChild(hidden);
					});
				}

				if (subcategoryOverlay) subcategoryOverlay.classList.add("hidden");
			});
		}

		// Handle close/cancel for subcategory overlay
		closeSubcategoryBtns.forEach((btn) => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				if (subcategoryOverlay) subcategoryOverlay.classList.add("hidden");
				// Optionally clear selection or keep previous
			});
		});
	},

	/**
	 * Set up subcategory tag filtering for category page
	 */
	setupSubcategoryTagFiltering() {
		const tagContainer = document.getElementById("subcategory-carousel");
		if (tagContainer) {
			const tags = tagContainer.querySelectorAll(".subcategory-tag");
			const servicesList = document.getElementById("services-list");
			let selectedSubcats = new Set();

			tags.forEach((tag) => {
				tag.addEventListener("click", function () {
					const subcatId = this.getAttribute("data-subcategory-id");
					if (selectedSubcats.has(subcatId)) {
						selectedSubcats.delete(subcatId);
						this.classList.remove("selected");
					} else {
						selectedSubcats.add(subcatId);
						this.classList.add("selected");
					}
					filterServices();
				});
			});

			function filterServices() {
				if (!servicesList) return;
				const cards = servicesList.querySelectorAll(".service-card");

				if (selectedSubcats.size === 0) {
					// Show all if nothing selected
					cards.forEach((card) => (card.style.display = ""));
					return;
				}

				cards.forEach((card) => {
					const subcatIds = card
						.getAttribute("data-subcategory-ids")
						.split(",");
					// Show if any selected subcat matches
					const show = Array.from(selectedSubcats).some((id) =>
						subcatIds.includes(id)
					);
					card.style.display = show ? "" : "none";
				});
			}
		}
	},

	/**
	 * Set up filtering for services by price range and max delivery days
	 */
	setupFilters() {
		const minPriceInput = document.querySelector('.min-price-filter');
		const maxPriceInput = document.querySelector('.max-price-filter');
		const minValueDisplay = document.getElementById('min-value-filter');
		const maxValueDisplay = document.getElementById('max-value-filter');

		if (minPriceInput && maxPriceInput) {
			const updateDisplayedValues = () => {
				let minPrice = parseFloat(minPriceInput.value);
				let maxPrice = parseFloat(maxPriceInput.value);

				// Ensure the circles do not cross each other
				if (minPrice > maxPrice) {
					minPrice = maxPrice;
					minPriceInput.value = minPrice;
				}

				// Update displayed values
				minValueDisplay.textContent = minPrice;
				maxValueDisplay.textContent = maxPrice;
			};

			// Attach event listeners to range inputs to update displayed values
			minPriceInput.addEventListener('input', updateDisplayedValues);
			maxPriceInput.addEventListener('input', updateDisplayedValues);
		}
	},
};

// Export the Categories object for use in other modules
window.Categories = Categories;
