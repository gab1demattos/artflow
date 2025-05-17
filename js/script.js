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
