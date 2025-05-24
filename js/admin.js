// admin.js - Handles fetching and admin actions for the admin panel

// Security utility functions
/**
 * Escapes HTML to prevent XSS attacks
 * @param {string} unsafe - The unsafe string to be escaped
 * @return {string} The escaped string
 */
function escapeHtml(unsafe) {
	if (typeof unsafe !== "string") {
		return "";
	}
	return unsafe
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}

/**
 * Creates a safe DOM element with escaped content
 * @param {string} tag - HTML tag name
 * @param {Object} attributes - Element attributes
 * @param {string} textContent - Element text content
 * @return {HTMLElement} The created element
 */
function createSafeElement(tag, attributes = {}, textContent = "") {
	const element = document.createElement(tag);

	// Set attributes safely
	for (const [key, value] of Object.entries(attributes)) {
		if (key.startsWith("on")) continue; // Skip event handlers
		element.setAttribute(key, value);
	}

	// Set text content safely
	if (textContent) {
		element.textContent = textContent;
	}

	return element;
}

document.addEventListener("DOMContentLoaded", () => {
	if (window.Modals && typeof window.Modals.init === "function") {
		window.Modals.init();
	}

	// Tab switching logic
	const tabButtons = document.querySelectorAll(".admin-tab-btn");
	const tabContents = document.querySelectorAll(".admin-tab-content");
	const tabMap = {
		users: document.getElementById("admin-users"),
		services: document.getElementById("admin-services"),
		categories: document.getElementById("admin-categories"),
	};

	function showTab(tab) {
		tabButtons.forEach((b) => b.classList.remove("active"));
		tabContents.forEach((c) => c.classList.remove("active"));
		tabMap[tab].classList.add("active");
		document
			.querySelector(`.admin-tab-btn[data-tab="${tab}"]`)
			.classList.add("active");
		if (tab === "users") {
			renderUsersTab();
			tabMap["services"].innerHTML = "";
			tabMap["categories"].innerHTML =
				'<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
		} else if (tab === "services") {
			renderServicesTab();
			tabMap["users"].innerHTML = "";
			tabMap["categories"].innerHTML =
				'<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
		} else if (tab === "categories") {
			tabMap["users"].innerHTML = "";
			tabMap["services"].innerHTML = "";
			tabMap["categories"].innerHTML =
				'<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
			renderCategoriesTab();
		}
		// Re-initialize modals after dynamic content
		if (window.Modals && typeof window.Modals.init === "function") {
			window.Modals.init();
		}
	}

	// Initial load: show users tab
	showTab("users");
	fetchStats();

	tabButtons.forEach((btn) => {
		btn.addEventListener("click", function () {
			const tab = this.getAttribute("data-tab");
			showTab(tab);
		});
	});

	// Event delegation for all admin tables
	document
		.getElementById("admin-users")
		.addEventListener("click", function (e) {
			if (e.target.classList.contains("promote-btn")) promoteUser(e);
			if (
				e.target.classList.contains("ban-btn") ||
				e.target.classList.contains("red-btn")
			)
				banUser(e);
		});
	document
		.getElementById("admin-services")
		.addEventListener("click", function (e) {
			if (e.target.classList.contains("delete-service-btn")) deleteService(e);
		});

	// Intercept category form submit for AJAX
	document.body.addEventListener("submit", function (e) {
		const form = e.target;
		if (form && form.id === "category-form") {
			e.preventDefault();
			const formData = new FormData(form);
			fetch("/actions/adminpanel/add-category.php", {
				method: "POST",
				body: formData,
			})
				.then((r) => r.json())
				.then((result) => {
					if (result.success) {
						// Hide modal, refresh categories and stats
						document
							.getElementById("category-modal-overlay")
							.classList.add("hidden");
						fetchCategories();
						fetchStats();
						form.reset();
					} else {
						alert(result.error || "Failed to add category.");
					}
				})
				.catch(() => alert("Failed to add category."));
		}
	});
});

function fetchStats() {
	fetch("/actions/adminpanel/get-admin-stats.php")
		.then((r) => r.json())
		.then((data) => {
			document.querySelector("#stat-users .stat-value").textContent =
				data.users || "0";
			document.querySelector("#stat-services .stat-value").textContent =
				data.services || "0";
			document.querySelector("#stat-categories .stat-value").textContent =
				data.categories || "0";
		});
}

function renderUsersTab() {
	const container = document.getElementById("admin-users");
	container.innerHTML = `
        <table class="admin-table" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    `;
	fetchUsers();
}

function renderServicesTab() {
	const container = document.getElementById("admin-services");
	container.innerHTML = `
        <table class="admin-table" id="services-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    `;
	fetchServices();
}

function renderCategoriesTab() {
	const container = document.getElementById("admin-categories-table");
	container.innerHTML = `
        <table class="admin-table" id="categories-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    `;
	fetchCategories();
	// Re-attach event delegation for delete-category-btn after rendering
	container.removeEventListener("click", handleCategoryTableClick, false);
	container.addEventListener("click", handleCategoryTableClick, false);
	// Re-initialize modals to ensure irreversible modal is ready
	if (window.Modals && typeof window.Modals.init === "function") {
		window.Modals.init();
	}
}

function handleCategoryTableClick(e) {
	if (e.target.classList.contains("delete-category-btn")) deleteCategory(e);
}

function fetchUsers() {
	fetch("/actions/adminpanel/get-all-users.php")
		.then((r) => r.json())
		.then((users) => {
			const tbody = document.querySelector("#users-table tbody");
			tbody.innerHTML = "";
			users.forEach((user) => {
				// Create row element
				const tr = document.createElement("tr");

				// Create and append cells with escaped content
				const idCell = createSafeElement("td", {}, user.id);
				const nameCell = createSafeElement("td", {}, user.name);
				const usernameCell = createSafeElement("td", {}, user.username);
				const emailCell = createSafeElement("td", {}, user.email);
				const typeCell = createSafeElement("td", {}, user.user_type);

				// Create actions cell
				const actionsCell = document.createElement("td");

				// Create promote button
				const promoteBtn = createSafeElement(
					"button",
					{
						class: "promote-btn",
						"data-id": user.id,
						...(user.user_type === "admin" ? { disabled: "disabled" } : {}),
					},
					"Promote"
				);

				// Create ban button
				const banBtn = createSafeElement(
					"button",
					{
						class: "red-btn",
						"data-id": user.id,
						...(user.banned ? { disabled: "disabled" } : {}),
					},
					"Ban"
				);

				// Append buttons to actions cell
				actionsCell.appendChild(promoteBtn);
				actionsCell.appendChild(banBtn);

				// Append all cells to row
				tr.appendChild(idCell);
				tr.appendChild(nameCell);
				tr.appendChild(usernameCell);
				tr.appendChild(emailCell);
				tr.appendChild(typeCell);
				tr.appendChild(actionsCell);

				// Append row to table body
				tbody.appendChild(tr);
			});
		});
}

function fetchServices() {
	fetch("/actions/adminpanel/get-all-services.php")
		.then((r) => r.json())
		.then((services) => {
			const tbody = document.querySelector("#services-table tbody");
			tbody.innerHTML = "";
			services.forEach((service) => {
				// Create row element
				const tr = document.createElement("tr");

				// Create cells with escaped content
				const idCell = createSafeElement("td", {}, service.id);
				const titleCell = createSafeElement("td", {}, service.title);
				const ownerCell = createSafeElement("td", {}, service.owner);
				const categoryCell = createSafeElement("td", {}, service.category);

				// Create actions cell with delete button
				const actionsCell = document.createElement("td");
				const deleteBtn = createSafeElement(
					"button",
					{
						class: "delete-service-btn red-btn",
						"data-id": service.id,
					},
					"Delete"
				);
				actionsCell.appendChild(deleteBtn);

				// Append all cells to row
				tr.appendChild(idCell);
				tr.appendChild(titleCell);
				tr.appendChild(ownerCell);
				tr.appendChild(categoryCell);
				tr.appendChild(actionsCell);

				// Append row to table body
				tbody.appendChild(tr);
			});
		});
}

function fetchCategories() {
	fetch("/actions/adminpanel/get-all-categories.php")
		.then((r) => r.json())
		.then((categories) => {
			const tbody = document.querySelector("#categories-table tbody");
			tbody.innerHTML = "";
			categories.forEach((cat) => {
				// Create row element
				const tr = document.createElement("tr");

				// Create cells with escaped content
				const idCell = createSafeElement("td", {}, cat.id);
				const typeCell = createSafeElement("td", {}, cat.type);

				// Create image cell
				const imageCell = document.createElement("td");
				// Validate image URL before creating the element
				const imageSrc =
					cat.image && cat.image.startsWith("/")
						? cat.image
						: "/images/categories/default.jpg";
				const img = createSafeElement("img", {
					src: imageSrc,
					alt: cat.type,
					style: "max-width:40px;max-height:40px;",
				});
				imageCell.appendChild(img);

				// Create actions cell with delete button
				const actionsCell = document.createElement("td");
				const deleteBtn = createSafeElement(
					"button",
					{
						class: "delete-category-btn red-btn",
						"data-id": cat.id,
					},
					"Delete"
				);
				actionsCell.appendChild(deleteBtn);

				// Append all cells to row
				tr.appendChild(idCell);
				tr.appendChild(typeCell);
				tr.appendChild(imageCell);
				tr.appendChild(actionsCell);

				// Append row to table body
				tbody.appendChild(tr);
			});
		});
}

function promoteUser(e) {
	const id = e.target.dataset.id;
	fetch("/actions/adminpanel/promote-user.php", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: `user_id=${id}`,
	}).then(() => fetchUsers());
}

function banUser(e) {
	const id = e.target.dataset.id;
	if (
		window.Modals &&
		typeof window.Modals.showIrreversibleModal === "function"
	) {
		window.Modals.showIrreversibleModal(
			function onConfirm() {
				fetch("/actions/adminpanel/ban-user.php", {
					method: "POST",
					headers: { "Content-Type": "application/x-www-form-urlencoded" },
					body: `user_id=${id}`,
				}).then(() => fetchUsers());
			},
			function onCancel() {
				// Do nothing
			}
		);
	} else {
		fetch("/actions/adminpanel/ban-user.php", {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: `user_id=${id}`,
		}).then(() => fetchUsers());
	}
}

function deleteService(e) {
	const id = e.target.dataset.id;
	if (
		window.Modals &&
		typeof window.Modals.showIrreversibleModal === "function"
	) {
		window.Modals.showIrreversibleModal(
			function onConfirm() {
				fetch("/actions/adminpanel/delete-service.php", {
					method: "POST",
					headers: { "Content-Type": "application/x-www-form-urlencoded" },
					body: `service_id=${id}`,
				}).then(() => {
					fetchServices();
					fetchStats();
				});
			},
			function onCancel() {
				// Do nothing
			}
		);
	} else {
		fetch("/actions/adminpanel/delete-service.php", {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: `service_id=${id}`,
		}).then(() => {
			fetchServices();
			fetchStats();
		});
	}
}

function deleteCategory(e) {
	const id = e.target.dataset.id;
	if (
		window.Modals &&
		typeof window.Modals.showIrreversibleModal === "function"
	) {
		window.Modals.showIrreversibleModal(
			function onConfirm() {
				fetch("/actions/adminpanel/delete-category.php", {
					method: "POST",
					headers: { "Content-Type": "application/x-www-form-urlencoded" },
					body: `category_id=${id}`,
				}).then(() => {
					fetchCategories();
					fetchStats();
				});
			},
			function onCancel() {
				// Do nothing
			}
		);
	} else {
		fetch("/actions/adminpanel/delete-category.php", {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: `category_id=${id}`,
		}).then(() => {
			fetchCategories();
			fetchStats();
		});
	}
}

function addCategory(e) {
	e.preventDefault();
	const form = e.target;
	const data = new URLSearchParams(new FormData(form));
	fetch("/actions/adminpanel/add-category.php", {
		method: "POST",
		body: data,
	}).then(() => {
		fetchCategories();
		fetchStats();
		form.reset();
	});
}
