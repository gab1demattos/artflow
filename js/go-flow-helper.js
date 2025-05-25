/**
 * Dedicated script to handle showing the Go With Flow modal
 * This is a simplified approach to ensure the modal appears after signup or account deletion
 */

document.addEventListener("DOMContentLoaded", function () {
	// Check if URL has the showGoFlow parameter
	function getQueryParam(param) {
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(param);
	}

	const showGoFlow = getQueryParam("showGoFlow") === "true";

	if (showGoFlow) {
		console.log("showGoFlow parameter detected in URL, showing modal");

		// Find the modal
		const goFlowModal = document.getElementById("goflow-modal-overlay");

		if (goFlowModal) {
			console.log("Found go-with-flow modal element");

			// Hide all other modals
			document.querySelectorAll(".modal-overlay").forEach(function (modal) {
				if (modal !== goFlowModal) {
					modal.classList.add("hidden");
				}
			});

			// Show the go flow modal with a short delay to ensure DOM is ready
			setTimeout(function () {
				goFlowModal.classList.remove("hidden");
				console.log("Go with flow modal shown via go-flow-helper.js");

				// Clean up URL parameter without reloading page
				const url = new URL(window.location.href);
				url.searchParams.delete("showGoFlow");
				history.replaceState({}, document.title, url);
			}, 300);

			// Note: Arrow button click handling is now managed in modals.js
			// This prevents duplicate event listeners and ensures consistent behavior
		} else {
			console.error("Go with flow modal element not found");
		}
	}
});
