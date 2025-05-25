
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

function createSafeElement(tag, attributes = {}, textContent = "") {
	const element = document.createElement(tag);

	for (const [key, value] of Object.entries(attributes)) {
		if (key.startsWith("on")) continue; 
		element.setAttribute(key, value);
	}

	if (textContent) {
		element.textContent = textContent;
	}

	return element;
}

document.addEventListener("DOMContentLoaded", () => {
	if (window.Modals && typeof window.Modals.init === "function") {
		window.Modals.init();
	}

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
		if (window.Modals && typeof window.Modals.init === "function") {
			window.Modals.init();
		}
	}

	showTab("users");
	fetchStats();

	tabButtons.forEach((btn) => {
		btn.addEventListener("click", function () {
			const tab = this.getAttribute("data-tab");
			showTab(tab);
		});
	});

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
			if (e.target.classList.contains("delete-service-admin-btn")) deleteService(e);
		});

	document.body.addEventListener("submit", function (e) {
		const form = e.target;
		if (form && form.id === "category-form") {
			e.preventDefault();
			const formData = new FormData(form);
			fetch("../../actions/adminpanel/add-category.php", {
				method: "POST",
				body: formData,
			})
				.then((r) => r.json())
				.then((result) => {
					if (result.success) {
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
	fetch("../../actions/adminpanel/get-admin-stats.php")
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
	container.removeEventListener("click", handleCategoryTableClick, false);
	container.addEventListener("click", handleCategoryTableClick, false);
	if (window.Modals && typeof window.Modals.init === "function") {
		window.Modals.init();
	}
}

function handleCategoryTableClick(e) {
	if (e.target.classList.contains("delete-category-btn")) deleteCategory(e);
}

function fetchUsers() {
	fetch("../../actions/adminpanel/get-all-users.php")
		.then((r) => r.json())
		.then((users) => {
			const tbody = document.querySelector("#users-table tbody");
			tbody.innerHTML = "";
			users.forEach((user) => {
				const tr = document.createElement("tr");

				const idCell = createSafeElement("td", {}, user.id);
				const nameCell = createSafeElement("td", {}, user.name);
				const usernameCell = createSafeElement("td", {}, user.username);
				const emailCell = createSafeElement("td", {}, user.email);
				const typeCell = createSafeElement("td", {}, user.user_type);

				const actionsCell = document.createElement("td");

				const promoteBtn = createSafeElement(
					"button",
					{
						class: "promote-btn",
						"data-id": user.id,
						...(user.user_type === "admin" ? { disabled: "disabled" } : {}),
					},
					"Promote"
				);

				const banBtn = createSafeElement(
					"button",
					{
						class: "red-btn",
						"data-id": user.id,
						...(user.banned ? { disabled: "disabled" } : {}),
					},
					"Ban"
				);

				actionsCell.appendChild(promoteBtn);
				actionsCell.appendChild(banBtn);

				tr.appendChild(idCell);
				tr.appendChild(nameCell);
				tr.appendChild(usernameCell);
				tr.appendChild(emailCell);
				tr.appendChild(typeCell);
				tr.appendChild(actionsCell);

				tbody.appendChild(tr);
			});
		});
}

function fetchServices() {
	fetch("../../actions/adminpanel/get-all-services.php")
		.then((r) => r.json())
		.then((services) => {
			const tbody = document.querySelector("#services-table tbody");
			tbody.innerHTML = "";
			services.forEach((service) => {
				const tr = document.createElement("tr");

				const idCell = createSafeElement("td", {}, service.id);
				const titleCell = createSafeElement("td", {}, service.title);
				const ownerCell = createSafeElement("td", {}, service.owner);
				const categoryCell = createSafeElement("td", {}, service.category);

				const actionsCell = document.createElement("td");
				const deleteBtn = createSafeElement(
					"button",
					{
						class: "delete-service-admin-btn red-btn",
						"data-id": service.id,
					},
					"Delete"
				);
				actionsCell.appendChild(deleteBtn);

				tr.appendChild(idCell);
				tr.appendChild(titleCell);
				tr.appendChild(ownerCell);
				tr.appendChild(categoryCell);
				tr.appendChild(actionsCell);

				tbody.appendChild(tr);
			});
		});
}

function fetchCategories() {
	fetch("../../actions/adminpanel/get-all-categories.php")
		.then((r) => r.json())
		.then((categories) => {
			const tbody = document.querySelector("#categories-table tbody");
			tbody.innerHTML = "";
			categories.forEach((cat) => {
				const tr = document.createElement("tr");

				const idCell = createSafeElement("td", {}, cat.id);
				const typeCell = createSafeElement("td", {}, cat.type);

				const imageCell = document.createElement("td");
				const imageSrc =
					cat.image && cat.image.startsWith("/")
						? cat.image
						: "../../images/categories/default.jpg";
				const img = createSafeElement("img", {
					src: imageSrc,
					alt: cat.type,
					style: "max-width:40px;max-height:40px;",
				});
				imageCell.appendChild(img);

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

				tr.appendChild(idCell);
				tr.appendChild(typeCell);
				tr.appendChild(imageCell);
				tr.appendChild(actionsCell);

				tbody.appendChild(tr);
			});
		});
}

function promoteUser(e) {
	const id = e.target.dataset.id;
	fetch("../../actions/adminpanel/promote-user.php", {
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
				fetch("../../actions/adminpanel/ban-user.php", {
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
		fetch("../../actions/adminpanel/ban-user.php", {
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
				fetch("../../actions/adminpanel/delete-service.php", {
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
		fetch("../../actions/adminpanel/delete-service.php", {
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
				fetch("../../actions/adminpanel/delete-category.php", {
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
		fetch("../../actions/adminpanel/delete-category.php", {
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
	fetch("../../actions/adminpanel/add-category.php", {
		method: "POST",
		body: data,
	}).then(() => {
		fetchCategories();
		fetchStats();
		form.reset();
	});
}
