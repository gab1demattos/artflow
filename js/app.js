/**
 * Main application entry point
 * Initializes all modules and handles common functionality
 */

document.addEventListener("DOMContentLoaded", function () {
	// Initialize all modules
	if (window.Modals) Modals.init();
	if (window.Categories) Categories.init();

	console.log("Application initialized");
});
