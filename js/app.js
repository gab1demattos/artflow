/**
 * Main application entry point
 * Initializes all modules and handles common functionality
 */

document.addEventListener("DOMContentLoaded", function () {
	// Debug: Log all sessionStorage items
	console.log("All sessionStorage items:");
	for (let i = 0; i < sessionStorage.length; i++) {
		const key = sessionStorage.key(i);
		console.log(`${key}: ${sessionStorage.getItem(key)}`);
	}
	console.log(
		"signup_success value:",
		sessionStorage.getItem("signup_success")
	);

	// Initialize all modules
	if (window.Modals) Modals.init();
	if (window.Categories) Categories.init();

	// Check for signup_success flag and show the go with flow modal if needed
	if (
		sessionStorage.getItem("signup_success") === "true" &&
		window.showGoFlowModal
	) {
		console.log("Showing go with flow modal from app.js");
		window.showGoFlowModal();
		// Clear the flag to prevent showing the modal again on refresh
		sessionStorage.removeItem("signup_success");
	} else {
		console.log("Not showing go with flow modal:", {
			signupSuccess: sessionStorage.getItem("signup_success"),
			showGoFlowModalExists: !!window.showGoFlowModal,
		});
	}

	console.log("Application initialized");
});
