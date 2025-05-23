/**
 * Modals management
 * Handles showing and hiding of various modals in the application
 */

const Modals = {
	// Modal overlay elements
	overlays: {
		signUp: null,
		signIn: null,
		goFlow: null,
		category: null,
		newService: null,
		subcategory: null,
		irreversible: null,
	},

	// Callback functions for irreversible modal
	irreversibleCallbacks: {
		confirm: null,
		cancel: null,
	},

	/**
	 * Initialize modals functionality
	 */
	init() {
		// Get modal overlay references
		this.overlays.signUp = document.getElementById("signup-modal-overlay");
		this.overlays.signIn = document.getElementById("signin-modal-overlay");
		this.overlays.goFlow = document.getElementById("goflow-modal-overlay");
		this.overlays.category = document.getElementById("category-modal-overlay");
		this.overlays.newService = document.getElementById(
			"new-service-modal-overlay"
		);
		this.overlays.subcategory = document.getElementById("subcategory-overlay");
		this.overlays.irreversible = document.getElementById("irreversible-modal");

		// Initialize various modal-related events
		this.setupGenericModalEvents();
		this.setupSignInSignUpToggling();
		this.setupPasswordToggling();
		this.setupCategoryModal();
		this.setupNewServiceModal();
		this.setupGoFlowModal();
		this.setupIrreversibleModal();
	},

	/**
	 * Hide all modal overlays
	 */
	hideAll() {
		Object.values(this.overlays).forEach((overlay) => {
			if (overlay) overlay.classList.add("hidden");
		});
	},

	/**
	 * Show a specific modal and hide others
	 * @param {string} modalName - Name of the modal to show
	 */
	show(modalName) {
		this.hideAll();
		const overlay = this.overlays[modalName];
		if (overlay) overlay.classList.remove("hidden");
	},

	/**
	 * Set up generic modal events like clicking outside to close
	 */
	setupGenericModalEvents() {
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
	},

	/**
	 * Set up sign in and sign up toggling functionality
	 */
	setupSignInSignUpToggling() {
		// Sign up button in header
		const signupBtn = document.querySelector("#buttons li button");
		if (signupBtn && this.overlays.signUp) {
			signupBtn.addEventListener("click", (e) => {
				e.stopPropagation();
				this.show("signUp");
			});
		}

		// Sign in buttons in sign up modal
		const signInButtons = document.querySelectorAll("#sign-in");
		if (this.overlays.signIn) {
			signInButtons.forEach((button) => {
				button.addEventListener("click", (e) => {
					e.stopPropagation();
					this.show("signIn");
				});
			});
		}

		// Sign up buttons in sign in modal
		const signUpButtons = document.querySelectorAll("#sign-up");
		if (this.overlays.signUp) {
			signUpButtons.forEach((button) => {
				button.addEventListener("click", (e) => {
					e.stopPropagation();
					this.show("signUp");
				});
			});
		}
	},

	/**
	 * Set up password visibility toggling
	 */
	setupPasswordToggling() {
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
	},

	/**
	 * Set up category modal for admin
	 */
	setupCategoryModal() {
		const openCategoryModalBtn = document.getElementById("open-category-modal");
		const closeCategoryModalBtn = document.getElementById(
			"close-category-modal"
		);

		if (openCategoryModalBtn && this.overlays.category) {
			openCategoryModalBtn.addEventListener("click", () => {
				this.overlays.category.classList.remove("hidden");
			});
		}

		if (closeCategoryModalBtn && this.overlays.category) {
			closeCategoryModalBtn.addEventListener("click", () => {
				this.overlays.category.classList.add("hidden");
			});
		}
	},

	/**
	 * Set up new service modal
	 */
	setupNewServiceModal() {
		const openNewServiceModalBtn = document.getElementById(
			"open-new-service-modal"
		);
		const closeNewServiceModalBtn = document.querySelector(
			"#new-service-modal .close-modal"
		);

		if (openNewServiceModalBtn && this.overlays.newService) {
			openNewServiceModalBtn.addEventListener("click", (e) => {
				e.stopPropagation();
				this.overlays.newService.classList.remove("hidden");
			});
		}

		if (closeNewServiceModalBtn && this.overlays.newService) {
			closeNewServiceModalBtn.addEventListener("click", (e) => {
				e.stopPropagation();
				this.overlays.newService.classList.add("hidden");
			});
		}
	},

	/**
	 * Set up Go Flow modal and functionality after signup
	 */
	setupGoFlowModal() {
		// Check for signup success in session storage
		if (
			sessionStorage.getItem("signup_success") === "true" &&
			this.overlays.goFlow
		) {
			this.show("goFlow");
			// Clear the flag to prevent showing the modal again on refresh
			sessionStorage.removeItem("signup_success");
		}

		// Handle Go Flow modal arrow button click to log in
		const goFlowArrowButton = document.getElementById("go-arrow");
		if (goFlowArrowButton && this.overlays.goFlow) {
			goFlowArrowButton.addEventListener("click", async function () {
				// Get username and password from sessionStorage
				const username = sessionStorage.getItem("signup_username");
				const password = sessionStorage.getItem("signup_password");

				if (!username || !password) {
					alert("Could not log in automatically. Please sign in manually.");
					Modals.overlays.goFlow.classList.add("hidden");
					return;
				}

				// Send AJAX POST to login
				try {
					const response = await fetch("actions/login/signin-action.php", {
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
						Modals.overlays.goFlow.classList.add("hidden");
					}
				} catch (err) {
					console.error("Login error:", err);
					alert("Login error. Please sign in manually.");
					Modals.overlays.goFlow.classList.add("hidden");
				}
			});
		}
	},

	/**
	 * Set up irreversible action confirmation modal
	 */
	setupIrreversibleModal() {
		if (!this.overlays.irreversible) return;

		const confirmBtn = document.getElementById("irreversible-confirm-btn");
		const cancelBtn = document.getElementById("irreversible-cancel-btn");

		if (confirmBtn) {
			confirmBtn.addEventListener("click", () => {
				this.hideIrreversibleModal();
				if (typeof this.irreversibleCallbacks.confirm === "function") {
					this.irreversibleCallbacks.confirm();
				}
			});
		}

		if (cancelBtn) {
			cancelBtn.addEventListener("click", () => {
				this.hideIrreversibleModal();
				if (typeof this.irreversibleCallbacks.cancel === "function") {
					this.irreversibleCallbacks.cancel();
				}
			});
		}
	},

	/**
	 * Show the irreversible confirmation modal with custom callbacks
	 * @param {Function} confirmCallback - Function to call if user confirms
	 * @param {Function} cancelCallback - Function to call if user cancels
	 */
	showIrreversibleModal(confirmCallback = null, cancelCallback = null) {
		if (!this.overlays.irreversible) return;

		// Set callback functions
		this.irreversibleCallbacks.confirm = confirmCallback;
		this.irreversibleCallbacks.cancel = cancelCallback;

		// Show the modal
		this.overlays.irreversible.classList.add("show");
	},

	/**
	 * Hide the irreversible confirmation modal
	 */
	hideIrreversibleModal() {
		if (this.overlays.irreversible) {
			this.overlays.irreversible.classList.remove("show");
		}
	},
};

// Export the Modals object for use in other modules
window.Modals = Modals;