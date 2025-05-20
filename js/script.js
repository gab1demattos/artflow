document.addEventListener("DOMContentLoaded", function () {
    // Button elements
    const signupBtn = document.querySelector("#buttons li button");
    const signInButtons = document.querySelectorAll("#sign-in");
    const signUpButtons = document.querySelectorAll("#sign-up");
    const signUpBtn_submit = document.querySelector("#sign-up-submit");
    const nextBtn = document.querySelector("#next-btn");

    // Modal elements
    const signupModalOverlay = document.getElementById("signup-modal-overlay");
    const signinModalOverlay = document.getElementById("signin-modal-overlay");
    const goflowModalOverlay = document.getElementById("goflow-modal-overlay");

    // Function to hide all modals - with null checks
    function hideAllModals() {
        signupModalOverlay?.classList.add("hidden");
        signinModalOverlay?.classList.add("hidden");
        goflowModalOverlay?.classList.add("hidden");
    }

    // Show sign up modal when clicking sign up button
    if (signupBtn && signupModalOverlay) {
        signupBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            hideAllModals();
            signupModalOverlay.classList.remove("hidden");
        });
    }

    // Handle click on sign in button in sign up modal
    if (signinModalOverlay) {
        signInButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                e.stopPropagation();
                hideAllModals();
                signinModalOverlay.classList.remove("hidden");
            });
        });
    }

    // Handle click on sign up button in sign in modal
    if (signupModalOverlay) {
        signUpButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                e.stopPropagation();
                hideAllModals();
                signupModalOverlay.classList.remove("hidden");
            });
        });
    }

    // Close modal when clicking outside
    document.querySelectorAll(".modal-overlay").forEach((overlay) => {
        overlay.addEventListener("click", function () {
            overlay.classList.add("hidden");
        });

        const modal = overlay.querySelector(".modal");
        if (modal) {
            modal.addEventListener("click", function (e) {
                e.stopPropagation();
            });
        }
    });

    // Toggle password visibility
    const togglePasswordButtons = document.querySelectorAll(".toggle-password");
    togglePasswordButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector("i.material-icons");
            if (input && icon) {
                if (input.type === "password") {
                    input.type = "text";
                    icon.textContent = "visibility";
                    icon.alt = "Hide password";
                } else {
                    input.type = "password";
                    icon.textContent = "visibility_off";
                    icon.alt = "Show password";
                }
            }
        });
    });

    // Check for signup success in session storage
    if (
        sessionStorage.getItem("signup_success") === "true" &&
        goflowModalOverlay
    ) {
        // Show the go-with-flow modal after signup
        hideAllModals();
        goflowModalOverlay.classList.remove("hidden");

        // Clear the flag to prevent showing the modal again on refresh
        sessionStorage.removeItem("signup_success");
    }

    // Handle Go Flow modal arrow button click to log in
    const goFlowArrowButton = document.getElementById("go-arrow");
    if (goFlowArrowButton && goflowModalOverlay) {
        goFlowArrowButton.addEventListener("click", async function () {
            // Get username and password from sessionStorage
            const username = sessionStorage.getItem("signup_username");
            const password = sessionStorage.getItem("signup_password");

            if (!username || !password) {
                alert("Could not log in automatically. Please sign in manually.");
                goflowModalOverlay.classList.add("hidden");
                return;
            }

            // Send AJAX POST to login
            try {
                const response = await fetch("actions/signin-action.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: `email=${encodeURIComponent(
                        username
                    )}&password=${encodeURIComponent(password)}`,
                });

                // Check response
                if (response.ok) {
                    // Clean up sensitive info
                    sessionStorage.removeItem("signup_password");
                    sessionStorage.removeItem("signup_username");
                    // Redirect to index page
                    window.location.href = "index.php";
                } else {
                    alert("Login failed. Please sign in manually.");
                    goflowModalOverlay.classList.add("hidden");
                }
            } catch (err) {
                console.error("Login error:", err);
                alert("Login error. Please sign in manually.");
                goflowModalOverlay.classList.add("hidden");
            }
        });
    }

    // Category modal for admin
    const openCategoryModalBtn = document.getElementById("open-category-modal");
    const categoryModalOverlay = document.getElementById("category-modal-overlay");
    const closeCategoryModalBtn = document.getElementById("close-category-modal");

    if (openCategoryModalBtn && categoryModalOverlay) {
        openCategoryModalBtn.addEventListener("click", function (e) {
            categoryModalOverlay.classList.remove("hidden");
        });
    }
    if (closeCategoryModalBtn && categoryModalOverlay) {
        closeCategoryModalBtn.addEventListener("click", function (e) {
            categoryModalOverlay.classList.add("hidden");
        });
    }

    // New Service Modal logic
    const openNewServiceModalBtn = document.getElementById("open-new-service-modal");
    const newServiceModalOverlay = document.getElementById("new-service-modal-overlay");
    const closeNewServiceModalBtn = document.querySelector("#new-service-modal .close-modal");
    if (openNewServiceModalBtn && newServiceModalOverlay) {
        openNewServiceModalBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            newServiceModalOverlay.classList.remove("hidden");
        });
    }
    if (closeNewServiceModalBtn && newServiceModalOverlay) {
        closeNewServiceModalBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            newServiceModalOverlay.classList.add("hidden");
        });
    }

    // --- New Service Modal: Subcategory select overlay logic (button trigger) ---
    const openSubcatBtn = document.getElementById('open-subcategory-overlay');
    let currentCategoryId = null;
    const categorySelect = document.getElementById('category-select');
    const subcategorySection = document.getElementById('subcategory-section');
    if (categorySelect && openSubcatBtn && typeof subcategoriesByCategory !== 'undefined') {
        categorySelect.addEventListener('change', function () {
            const catId = this.value;
            currentCategoryId = catId;
            // Only show button if there are subcategories
            if (catId && subcategoriesByCategory[catId] && subcategoriesByCategory[catId].length > 0) {
                openSubcatBtn.style.display = '';
            } else {
                openSubcatBtn.style.display = 'none';
                // Also clear any previous summary
                if (subcategorySection) subcategorySection.innerHTML = '';
            }
        });
        openSubcatBtn.addEventListener('click', function () {
            if (!currentCategoryId || !subcategoriesByCategory[currentCategoryId]) return;
            // Show overlay/modal for subcategory selection
            if (subcategoryOverlay && subcategoryCheckboxesDiv) {
                subcategoryCheckboxesDiv.innerHTML = '';
                subcategoriesByCategory[currentCategoryId].forEach(subcat => {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'subcategories[]';
                    checkbox.value = subcat.id;
                    checkbox.id = 'subcat-' + subcat.id;
                    if (selectedSubcatValues.includes(subcat.id)) checkbox.checked = true;
                    const cbLabel = document.createElement('label');
                    cbLabel.htmlFor = checkbox.id;
                    cbLabel.textContent = subcat.name;
                    // Tag-like style is now handled by CSS
                    // Insert checkbox before label for CSS sibling selector
                    subcategoryCheckboxesDiv.appendChild(checkbox);
                    subcategoryCheckboxesDiv.appendChild(cbLabel);
                    // Add selected class for checked
                    if (checkbox.checked) cbLabel.classList.add('selected');
                    checkbox.addEventListener('change', function () {
                        if (checkbox.checked) {
                            cbLabel.classList.add('selected');
                        } else {
                            cbLabel.classList.remove('selected');
                        }
                    });
                });
                subcategoryOverlay.classList.remove('hidden');
            }
        });
    }

    // --- New Service Modal: Subcategory select overlay logic ---
    const subcategoryOverlay = document.getElementById('subcategory-overlay');
    const subcategoryModal = document.getElementById('subcategory-modal');
    const subcategoryForm = document.getElementById('subcategory-form');
    const subcategoryCheckboxesDiv = subcategoryModal ? subcategoryModal.querySelector('.subcategory-checkboxes') : null;
    const closeSubcategoryBtns = document.querySelectorAll('.close-subcategory-modal');
    let selectedSubcatValues = [];

    if (subcategoryForm) {
        subcategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            // Collect checked subcategories
            const checked = Array.from(subcategoryCheckboxesDiv.querySelectorAll('input[type="checkbox"]:checked'));
            selectedSubcatValues = checked.map(cb => cb.value);
            // Show summary in main modal (optional)
            if (subcategorySection) {
                if (selectedSubcatValues.length > 0) {
                    const summary = document.createElement('div');
                    summary.className = 'subcategory-summary';
                    summary.textContent = 'Selected: ' + checked.map(cb => cb.parentElement.textContent.trim()).join(', ');
                    subcategorySection.innerHTML = '';
                    subcategorySection.appendChild(summary);
                } else {
                    subcategorySection.innerHTML = '';
                }
            }
            // Add hidden inputs for selected subcategories so they are submitted with the form
            // Remove any previous hidden inputs
            const form = document.getElementById('new-service-form');
            if (form) {
                // Remove old hidden subcategory inputs
                Array.from(form.querySelectorAll('input[type="hidden"][name="subcategories[]"]')).forEach(el => el.remove());
                // Add new ones
                selectedSubcatValues.forEach(val => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'subcategories[]';
                    hidden.value = val;
                    form.appendChild(hidden);
                });
            }
            if (subcategoryOverlay) subcategoryOverlay.classList.add('hidden');
        });
    }

    // Handle close/cancel for subcategory overlay
    closeSubcategoryBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (subcategoryOverlay) subcategoryOverlay.classList.add('hidden');
            // Optionally clear selection or keep previous
        });
    });

    // Subcategory tag multi-select filter logic (category page)
    const tagContainer = document.getElementById('subcategory-carousel');
    if (tagContainer) {
        const tags = tagContainer.querySelectorAll('.subcategory-tag');
        const servicesList = document.getElementById('services-list');
        let selectedSubcats = new Set();

        tags.forEach(tag => {
            tag.addEventListener('click', function () {
                const subcatId = this.getAttribute('data-subcategory-id');
                if (selectedSubcats.has(subcatId)) {
                    selectedSubcats.delete(subcatId);
                    this.classList.remove('selected');
                } else {
                    selectedSubcats.add(subcatId);
                    this.classList.add('selected');
                }
                filterServices();
            });
        });

        function filterServices() {
            if (!servicesList) return;
            const cards = servicesList.querySelectorAll('.service-card');
            if (selectedSubcats.size === 0) {
                // Show all if nothing selected
                cards.forEach(card => card.style.display = '');
                return;
            }
            cards.forEach(card => {
                const subcatIds = card.getAttribute('data-subcategory-ids').split(',');
                // Show if any selected subcat matches
                const show = Array.from(selectedSubcats).some(id => subcatIds.includes(id));
                card.style.display = show ? '' : 'none';
            });
        }
    }
});
