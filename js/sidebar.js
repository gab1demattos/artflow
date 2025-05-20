/**
 * Sidebar functionality for artflow
 * Contains all functions related to managing the sidebar UI
 */

// Function to open the sidebar
function openSidebar() {
	const sidebar = document.getElementById("sidebar");
	const overlay = document.getElementById("overlay");
	if (sidebar && overlay) {
		sidebar.classList.add("active");
		overlay.classList.add("active");
	}
}

// Function to close the sidebar
function closeSidebar() {
	const sidebar = document.getElementById("sidebar");
	const overlay = document.getElementById("overlay");
	if (sidebar && overlay) {
		sidebar.classList.remove("active");
		overlay.classList.remove("active");
	}
}

// Initialize sidebar event listeners
document.addEventListener("DOMContentLoaded", function () {
	// Setup sidebar toggle button
	const sidebarOpenButton = document.getElementById("sidebar-open");
	if (sidebarOpenButton) {
		sidebarOpenButton.addEventListener("click", openSidebar);
	}

	// Setup overlay click to close sidebar
	const overlay = document.getElementById("overlay");
	if (overlay) {
		overlay.addEventListener("click", closeSidebar);
	}
});
