const Modals = {
	overlays: {
		signUp: null,
		signIn: null,
		goFlow: null,
		category: null,
		newService: null,
		subcategory: null,
		irreversible: null,
	},
	irreversibleCallbacks: {
		confirm: null,
		cancel: null,
	},
	init() {
		console.log("Initializing Modals");
		this.overlays.signUp = document.getElementById("signup-modal-overlay");
		this.overlays.signIn = document.getElementById("signin-modal-overlay");
		this.overlays.goFlow = document.getElementById("goflow-modal-overlay");
		this.overlays.category = document.getElementById("category-modal-overlay");
		this.overlays.newService = document.getElementById(
			"new-service-modal-overlay"
		);
		this.overlays.subcategory = document.getElementById("subcategory-overlay");
		this.overlays.irreversible = document.getElementById("irreversible-modal");
		console.log("Modal overlays found:", {
			signUp: !!this.overlays.signUp,
			signIn: !!this.overlays.signIn,
			goFlow: !!this.overlays.goFlow,
			category: !!this.overlays.category,
			newService: !!this.overlays.newService,
			subcategory: !!this.overlays.subcategory,
			irreversible: !!this.overlays.irreversible,
		});
		this.setupGenericModalEvents();
		this.setupSignInSignUpToggling();
		this.setupPasswordToggling();
		this.setupCategoryModal();
		this.setupNewServiceModal();
		this.setupGoFlowModal();
		this.setupIrreversibleModal();
	},
	hideAll() {
		Object.values(this.overlays).forEach((overlay) => {
			if (overlay) overlay.classList.add("hidden");
		});
	},
	show(modalName) {
		this.hideAll();
		const overlay = this.overlays[modalName];
		if (overlay) overlay.classList.remove("hidden");
	},
	setupGenericModalEvents() {
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
	setupSignInSignUpToggling() {
		const headerSignUpBtn = document.querySelector("#header-sign-up");
		if (headerSignUpBtn && this.overlays.signUp) {
			headerSignUpBtn.addEventListener("click", (e) => {
				e.stopPropagation();
				this.show("signUp");
			});
		}

		const signInButtons = document.querySelectorAll("#sign-in");
		if (this.overlays.signIn) {
			signInButtons.forEach((button) => {
				button.addEventListener("click", (e) => {
					e.stopPropagation();
					this.show("signIn");
				});
			});
		}

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
				if (typeof closeSidebar === "function") {
					closeSidebar();
				} else {
					const sidebar = document.getElementById("sidebar");
					const overlay = document.getElementById("overlay");
					if (sidebar && overlay) {
						sidebar.classList.remove("active");
						overlay.classList.remove("active");
					}
				}
			});
		}
		if (closeNewServiceModalBtn && this.overlays.newService) {
			closeNewServiceModalBtn.addEventListener("click", (e) => {
				e.stopPropagation();
				this.overlays.newService.classList.add("hidden");
			});
		}
	},
	setupGoFlowModal() {
		console.log(
			"Checking signup_success:",
			sessionStorage.getItem("signup_success")
		);
		if (
			sessionStorage.getItem("signup_success") === "true" &&
			this.overlays.goFlow
		) {
			console.log("Showing go with flow modal");
			this.show("goFlow");
			sessionStorage.removeItem("signup_success");
		}
		window.addEventListener("storage", (event) => {
			if (
				event.key === "signup_success" &&
				event.newValue === "true" &&
				this.overlays.goFlow
			) {
				console.log("Storage event triggered, showing modal");
				this.show("goFlow");
				sessionStorage.removeItem("signup_success");
			}
		});
		setTimeout(() => {
			if (
				sessionStorage.getItem("signup_success") === "true" &&
				this.overlays.goFlow
			) {
				console.log("Showing go with flow modal (delayed check)");
				this.show("goFlow");
				sessionStorage.removeItem("signup_success");
			}
		}, 500);
		const goFlowArrowButton = document.getElementById("go-arrow");
		const goFlowModal = document.getElementById("goflow-modal");
		if (goFlowArrowButton && this.overlays.goFlow && goFlowModal) {
			goFlowArrowButton.addEventListener("click", () => {
				goFlowArrowButton.classList.add("arrow-clicked");
				const modalContent = document.querySelector(
					"#goflow-modal .modal-content"
				);
				if (modalContent) {
					modalContent.classList.add("content-fading");
				}
				goFlowModal.classList.add("modal-closing");
				setTimeout(() => {
					this.overlays.goFlow.classList.add("hidden");
					goFlowModal.classList.remove("modal-closing");
					if (modalContent) {
						modalContent.classList.remove("content-fading");
					}
					goFlowArrowButton.classList.remove("arrow-clicked");
				}, 500);
			});
		}
	},
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
	showIrreversibleModal(confirmCallback = null, cancelCallback = null) {
		if (!this.overlays.irreversible) return;
		this.irreversibleCallbacks.confirm = confirmCallback;
		this.irreversibleCallbacks.cancel = cancelCallback;
		this.overlays.irreversible.classList.add("show");
	},
	hideIrreversibleModal() {
		if (this.overlays.irreversible) {
			this.overlays.irreversible.classList.remove("show");
		}
	},
};

window.showModalError = function (modalId, message) {
	let floating = document.getElementById("floating-modal-error");
	if (floating) floating.remove();
	floating = document.createElement("div");
	floating.id = "floating-modal-error";
	floating.textContent = message;
	document.body.appendChild(floating);
	setTimeout(() => {
		floating.classList.add("fade-out");
		setTimeout(() => floating.remove(), 600);
	}, 3000);
};

window.showGoFlowModal = function () {
	console.log("showGoFlowModal called");
	document
		.querySelectorAll(".modal-overlay")
		.forEach((m) => m.classList.add("hidden"));
	const goFlow = document.getElementById("goflow-modal-overlay");
	if (goFlow) {
		console.log("Found goflow-modal-overlay, removing hidden class");
		goFlow.classList.remove("hidden");
		goFlow.style.display = "flex";
		console.log("Modal hidden?", goFlow.classList.contains("hidden"));
		console.log("Modal display:", getComputedStyle(goFlow).display);
		const goArrow = document.getElementById("go-arrow");
		if (goArrow) {
			goArrow.addEventListener("click", function () {
				goFlow.classList.add("hidden");
				window.location.reload();
			});
		}
	} else {
		console.error("Could not find goflow-modal-overlay");
	}
};

const signupForm = document.querySelector("#signup-modal-overlay form");
if (signupForm) {
	signupForm.addEventListener("submit", function (e) {
		const username = signupForm.querySelector('input[name="username"]').value;
		const email = signupForm.querySelector('input[name="email"]').value;
		const password = signupForm.querySelector('input[name="password"]').value;
		const confirm = signupForm.querySelector(
			'input[name="confirm_password"]'
		).value;
		if (username.length < 3) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Username must be at least 3 characters."
			);
			return;
		}
		if (!/^[A-Za-z0-9_]+$/.test(username)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Username can only contain letters, numbers, or underscores (_)."
			);
			return;
		}
		if (!/^\S+@\S+\.\S+$/.test(email)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Please enter a valid email address."
			);
			return;
		}
		if (password.length < 8) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password must be at least 8 characters."
			);
			return;
		}
		if (!/[0-9]/.test(password)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password must contain at least one number."
			);
			return;
		}
		if (!/[A-Z]/.test(password)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password must contain at least one uppercase letter."
			);
			return;
		}
		if (!/[a-z]/.test(password)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password must contain at least one lowercase letter."
			);
			return;
		}
		if (!/[.\?\$#@!&%]/.test(password)) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password must contain at least one special character (.?$#@!&%)."
			);
			return;
		}
		if (password !== confirm) {
			e.preventDefault();
			showModalError(
				"signup-modal-overlay",
				"Password and confirmation do not match."
			);
			return;
		}
	});
}

const signinForm = document.querySelector("#signin-modal-overlay form");
if (signinForm) {
	signinForm.addEventListener("submit", async function (e) {
		e.preventDefault();
		try {
			const tokenResponse = await fetch("/actions/login/refresh_csrf.php");
			if (tokenResponse.ok) {
				const tokenData = await tokenResponse.json();
				const tokenInput = signinForm.querySelector('input[name="csrf_token"]');
				if (tokenInput) {
					tokenInput.value = tokenData.token;
				}
			}
		} catch (error) {
			console.error("Failed to refresh CSRF token:", error);
		}
		const formData = new FormData(signinForm);
		const res = await fetch(signinForm.action, {
			method: "POST",
			body: formData,
			headers: { "X-Requested-With": "XMLHttpRequest" },
		});
		if (!res.ok) {
			let errorMessage = "Invalid email or password.";
			try {
				const data = await res.json();
				errorMessage = data.error || errorMessage;
				console.log("Login error:", data);
			} catch (e) {
				console.error("Failed to parse error response:", e);
			}
			showModalError("signin-modal-overlay", errorMessage);
			try {
				const newTokenResponse = await fetch("/actions/login/refresh_csrf.php");
				if (newTokenResponse.ok) {
					const newTokenData = await newTokenResponse.json();
					const tokenInput = signinForm.querySelector(
						'input[name="csrf_token"]'
					);
					if (tokenInput) {
						tokenInput.value = newTokenData.token;
						console.log("New token set for next attempt");
					}
				}
			} catch (error) {
				console.error("Failed to refresh token after error:", error);
			}
		} else {
			window.location.reload();
		}
	});
}

window.Modals = Modals;
