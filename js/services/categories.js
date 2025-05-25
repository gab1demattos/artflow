const Categories = {
	currentCategoryId: null,
	selectedSubcatValues: [],
	
	init() {
		this.setupSubcategoryOverlay();
		this.setupSubcategoryTagFiltering();
		this.setupFilters();
	},
	
	setupSubcategoryOverlay() {
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
		
		if (
			categorySelect &&
			openSubcatBtn &&
			typeof subcategoriesByCategory !== "undefined"
		) {
			categorySelect.addEventListener("change", function () {
				const catId = this.value;
				Categories.currentCategoryId = catId;
				
				if (
					catId &&
					subcategoriesByCategory[catId] &&
					subcategoriesByCategory[catId].length > 0
				) {
					openSubcatBtn.style.display = "";
				} else {
					openSubcatBtn.style.display = "none";
					if (subcategorySection) subcategorySection.innerHTML = "";
				}
			});
			
			openSubcatBtn.addEventListener("click", function () {
				if (
					!Categories.currentCategoryId ||
					!subcategoriesByCategory[Categories.currentCategoryId]
				)
					return;
					
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
							
							subcategoryCheckboxesDiv.appendChild(checkbox);
							subcategoryCheckboxesDiv.appendChild(cbLabel);
							
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
		
		if (subcategoryForm) {
			subcategoryForm.addEventListener("submit", function (e) {
				e.preventDefault();
				
				const checked = Array.from(
					subcategoryCheckboxesDiv.querySelectorAll(
						'input[type="checkbox"]:checked'
					)
				);
				Categories.selectedSubcatValues = checked.map((cb) => cb.value);
				
				if (subcategorySection) {
					if (Categories.selectedSubcatValues.length > 0) {
						const summary = document.createElement("div");
						summary.className = "subcategory-summary";
						
						summary.textContent =
							"Selected: " +
							checked
								.map((cb) => {
									const label = subcategoryCheckboxesDiv.querySelector(
										'label[for="' + cb.id + '"]'
									);
									return label ? label.textContent.trim() : "";
								})
								.filter(Boolean)
								.join(", ");
						subcategorySection.innerHTML = "";
						subcategorySection.appendChild(summary);
					} else {
						subcategorySection.innerHTML = "";
					}
				}
				
				const form = document.getElementById("new-service-form");
				if (form) {
					Array.from(
						form.querySelectorAll(
							'input[type="hidden"][name="subcategories[]"]'
						)
					).forEach((el) => el.remove());
					
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
		
		closeSubcategoryBtns.forEach((btn) => {
			btn.addEventListener("click", function (e) {
				e.preventDefault();
				if (subcategoryOverlay) subcategoryOverlay.classList.add("hidden");
			});
		});
	},
	
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
					cards.forEach((card) => (card.style.display = ""));
					return;
				}
				cards.forEach((card) => {
					const subcatIds = card
						.getAttribute("data-subcategory-ids")
						.split(",");
					
					const show = Array.from(selectedSubcats).some((id) =>
						subcatIds.includes(id)
					);
					card.style.display = show ? "" : "none";
				});
			}
		}
	},
	
	setupFilters() {
		const minPriceInput = document.querySelector('.min-price-filter');
		const maxPriceInput = document.querySelector('.max-price-filter');
		const minValueDisplay = document.getElementById('min-value-filter');
		const maxValueDisplay = document.getElementById('max-value-filter');
		if (minPriceInput && maxPriceInput) {
			const updateDisplayedValues = () => {
				let minPrice = parseFloat(minPriceInput.value);
				let maxPrice = parseFloat(maxPriceInput.value);
				
				if (minPrice > maxPrice) {
					minPrice = maxPrice;
					minPriceInput.value = minPrice;
				}
				
				minValueDisplay.textContent = minPrice;
				maxValueDisplay.textContent = maxPrice;
			};
			
			minPriceInput.addEventListener('input', updateDisplayedValues);
			maxPriceInput.addEventListener('input', updateDisplayedValues);
		}
	},
};

window.Categories = Categories;
