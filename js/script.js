document.addEventListener("DOMContentLoaded", function () {
	// Button elements
	const signupBtn = document.querySelector("#buttons li button");
	const signInButtons = document.querySelectorAll("#sign-in");
	const signUpButtons = document.querySelectorAll("#sign-up");
	const signUpBtn_submit = document.querySelector("#sign-up-submit");
	const nextBtn = document.querySelector("#next-btn");

	// Function to hide all modals
	function hideAllModals() {
		document.getElementById("signup-modal-overlay")?.classList.add("hidden");
		document.getElementById("signin-modal-overlay")?.classList.add("hidden");
		document.getElementById("goflow-modal-overlay")?.classList.add("hidden");
	}

	// Show sign up modal when clicking sign up button
	if (signupBtn) {
		signupBtn.addEventListener("click", function (e) {
			e.stopPropagation();
			hideAllModals();
			document
				.getElementById("signup-modal-overlay")
				.classList.remove("hidden");
		});
	}

	// Handle click on sign in button in sign up modal
	signInButtons.forEach((button) => {
		button.addEventListener("click", function (e) {
			e.stopPropagation();
			hideAllModals();
			document
				.getElementById("signin-modal-overlay")
				.classList.remove("hidden");
		});
	});

	// Handle click on sign up button in sign in modal
	signUpButtons.forEach((button) => {
		button.addEventListener("click", function (e) {
			e.stopPropagation();
			hideAllModals();
			document
				.getElementById("signup-modal-overlay")
				.classList.remove("hidden");
		});
	});

	/* // MODIFIED: Handle sign up form submission (direct submit without role selection)
	if (signUpBtn_submit) {
		signUpBtn_submit.addEventListener("click", function (e) {
			// Get the form element
			const form = document.getElementById("signup-form");

			// Basic client-side validation
			const password = form.querySelector('input[name="password"]').value;
			const confirmPassword = form.querySelector(
				'input[name="confirm_password"]'
			).value;

			if (password !== confirmPassword) {
				alert("Passwords do not match!");
				e.preventDefault();
				return;
			}

			// If validation passes, let the form submit normally
			// All role-related code has been removed for now
		});
	} */

	// Close modal when clicking outside
	document.querySelectorAll(".modal-overlay").forEach((overlay) => {
		overlay.addEventListener("click", function () {
			overlay.classList.add("hidden");
		});

		overlay.querySelector(".modal").addEventListener("click", function (e) {
			e.stopPropagation();
		});
	});

	const togglePasswordButtons = document.querySelectorAll(".toggle-password");

	togglePasswordButtons.forEach((button) => {
		button.addEventListener("click", function () {
			const input = this.previousElementSibling;
			const icon = this.querySelector("i.material-icons");
			if (input.type === "password") {
				input.type = "text";
				icon.textContent = "visibility";
				icon.alt = "Hide password";
			} else {
				input.type = "password";
				icon.textContent = "visibility_off";
				icon.alt = "Show password";
			}
		});
	});

	// Check for signup success in session storage instead of URL parameters
	if (sessionStorage.getItem("signup_success") === "true") {
		// Show the go-with-flow modal after signup
		hideAllModals();
		document.getElementById("goflow-modal-overlay").classList.remove("hidden");

		// Clear the flag to prevent showing the modal again on refresh
		sessionStorage.removeItem("signup_success");
	}

	// Handle Go Flow modal arrow button click to log in
	const goFlowArrowButton = document.getElementById("go-arrow");
	if (goFlowArrowButton) {
		goFlowArrowButton.addEventListener("click", async function () {
			// Get username and password from sessionStorage
			const username = sessionStorage.getItem("signup_username");
			const password = sessionStorage.getItem("signup_password");

			if (!username || !password) {
				alert("Could not log in automatically. Please sign in manually.");
				document.getElementById("goflow-modal-overlay").classList.add("hidden");
				return;
			}

			// Send AJAX POST to login
			try {
				const response = await fetch("actions/signin-action.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
						"X-Requested-With": "XMLHttpRequest", // Add this for AJAX detection
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
					// Redirect to index page (without parameters)
					window.location.href = "index.php";
				} else {
					alert("Login failed. Please sign in manually.");
					document
						.getElementById("goflow-modal-overlay")
						.classList.add("hidden");
				}
			} catch (err) {
				console.error("Login error:", err);
				alert("Login error. Please sign in manually.");
				document.getElementById("goflow-modal-overlay").classList.add("hidden");
			}
		});
	}

	// Handle notification close buttons
	const closeNotificationButtons = document.querySelectorAll(
		".close-notification"
	);
	if (closeNotificationButtons) {
		closeNotificationButtons.forEach((button) => {
			button.addEventListener("click", function () {
				const notification = this.closest(".notification");
				notification.classList.add("fade-out");
				setTimeout(() => {
					notification.parentNode.removeChild(notification);
				}, 500);
			});
		});
	}

	// Auto-dismiss notifications after 5 seconds
	const notifications = document.querySelectorAll(".notification");
	if (notifications) {
		notifications.forEach((notification) => {
			setTimeout(() => {
				if (notification.parentNode) {
					notification.classList.add("fade-out");
					setTimeout(() => {
						notification.parentNode.removeChild(notification);
					}, 500);
				}
			}, 5000);
		});
	}
});
