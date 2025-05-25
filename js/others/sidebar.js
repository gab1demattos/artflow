function openSidebar() {
	const sidebar = document.getElementById("sidebar");
	const overlay = document.getElementById("overlay");
	if (sidebar && overlay) {
		sidebar.classList.add("active");
		overlay.classList.add("active");
	}
}

function closeSidebar() {
	const sidebar = document.getElementById("sidebar");
	const overlay = document.getElementById("overlay");
	if (sidebar && overlay) {
		sidebar.classList.remove("active");
		overlay.classList.remove("active");
	}
}

document.addEventListener("DOMContentLoaded", function () {
	const sidebarOpenButton = document.getElementById("sidebar-open");
	if (sidebarOpenButton) {
		sidebarOpenButton.addEventListener("click", openSidebar);
	}

	const overlay = document.getElementById("overlay");
	if (overlay) {
		overlay.addEventListener("click", closeSidebar);
	}
});
