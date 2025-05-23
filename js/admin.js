// admin.js - Handles fetching and admin actions for the admin panel

document.addEventListener('DOMContentLoaded', () => {
    if (window.Modals && typeof window.Modals.init === 'function') {
        window.Modals.init();
    }

    // Tab switching logic
    const tabButtons = document.querySelectorAll('.admin-tab-btn');
    const tabContents = document.querySelectorAll('.admin-tab-content');
    const tabMap = {
        users: document.getElementById('admin-users'),
        services: document.getElementById('admin-services'),
        categories: document.getElementById('admin-categories')
    };

    function showTab(tab) {
        tabButtons.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        tabMap[tab].classList.add('active');
        document.querySelector(`.admin-tab-btn[data-tab="${tab}"]`).classList.add('active');
        if (tab === 'users') {
            renderUsersTab();
            tabMap['services'].innerHTML = '';
            tabMap['categories'].innerHTML = '<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
        } else if (tab === 'services') {
            renderServicesTab();
            tabMap['users'].innerHTML = '';
            tabMap['categories'].innerHTML = '<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
        } else if (tab === 'categories') {
            tabMap['users'].innerHTML = '';
            tabMap['services'].innerHTML = '';
            tabMap['categories'].innerHTML = '<button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button><div id="admin-categories-table"></div>';
            renderCategoriesTab();
        }
        // Re-initialize modals after dynamic content
        if (window.Modals && typeof window.Modals.init === 'function') {
            window.Modals.init();
        }
    }

    // Initial load: show users tab
    showTab('users');
    fetchStats();

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.getAttribute('data-tab');
            showTab(tab);
        });
    });

    // Event delegation for all admin tables
    document.getElementById('admin-users').addEventListener('click', function (e) {
        if (e.target.classList.contains('promote-btn')) promoteUser(e);
        if (e.target.classList.contains('ban-btn')) banUser(e);
    });
    document.getElementById('admin-services').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-service-btn')) deleteService(e);
    });
    document.getElementById('admin-categories-table').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-category-btn')) deleteCategory(e);
    });

    // Intercept category form submit for AJAX
    document.body.addEventListener('submit', function (e) {
        const form = e.target;
        if (form && form.id === 'category-form') {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('/actions/add-category.php', {
                method: 'POST',
                body: formData
            })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        // Hide modal, refresh categories and stats
                        document.getElementById('category-modal-overlay').classList.add('hidden');
                        fetchCategories();
                        fetchStats();
                        form.reset();
                    } else {
                        alert(result.error || 'Failed to add category.');
                    }
                })
                .catch(() => alert('Failed to add category.'));
        }
    });
});

function fetchStats() {
    fetch('/actions/get-admin-stats.php')
        .then(r => r.json())
        .then(data => {
            document.querySelector('#stat-users .stat-value').textContent = data.users || '0';
            document.querySelector('#stat-services .stat-value').textContent = data.services || '0';
            document.querySelector('#stat-categories .stat-value').textContent = data.categories || '0';
        });
}

function renderUsersTab() {
    const container = document.getElementById('admin-users');
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
    const container = document.getElementById('admin-services');
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
    const container = document.getElementById('admin-categories-table');
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
}

function fetchUsers() {
    fetch('/actions/get-all-users.php')
        .then(r => r.json())
        .then(users => {
            const tbody = document.querySelector('#users-table tbody');
            tbody.innerHTML = '';
            users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.user_type}</td>
                    <td>
                        <button class="promote-btn" data-id="${user.id}" ${user.user_type === 'admin' ? 'disabled' : ''}>Promote</button>
                        <button class="ban-btn" data-id="${user.id}" ${user.banned ? 'disabled' : ''}>Ban</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function fetchServices() {
    fetch('/actions/get-all-services.php')
        .then(r => r.json())
        .then(services => {
            const tbody = document.querySelector('#services-table tbody');
            tbody.innerHTML = '';
            services.forEach(service => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${service.id}</td>
                    <td>${service.title}</td>
                    <td>${service.owner}</td>
                    <td>${service.category}</td>
                    <td><button class="delete-service-btn" data-id="${service.id}">Delete</button></td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function fetchCategories() {
    fetch('/actions/get-all-categories.php')
        .then(r => r.json())
        .then(categories => {
            const tbody = document.querySelector('#categories-table tbody');
            tbody.innerHTML = '';
            categories.forEach(cat => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${cat.id}</td>
                    <td>${cat.type}</td>
                    <td><img src="${cat.image}" alt="" style="max-width:40px;max-height:40px;"></td>
                    <td><button class="delete-category-btn" data-id="${cat.id}">Delete</button></td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function promoteUser(e) {
    const id = e.target.dataset.id;
    fetch('/actions/promote-user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${id}`
    }).then(() => fetchUsers());
}

function banUser(e) {
    const id = e.target.dataset.id;
    if (window.Modals && typeof window.Modals.showIrreversibleModal === 'function') {
        window.Modals.showIrreversibleModal(
            function onConfirm() {
                fetch('/actions/ban-user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${id}`
                }).then(() => fetchUsers());
            },
            function onCancel() {
                // Do nothing
            }
        );
    } else {
        fetch('/actions/ban-user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${id}`
        }).then(() => fetchUsers());
    }
}

function deleteService(e) {
    const id = e.target.dataset.id;
    if (window.Modals && typeof window.Modals.showIrreversibleModal === 'function') {
        window.Modals.showIrreversibleModal(
            function onConfirm() {
                fetch('/actions/delete-service.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `service_id=${id}`
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
        fetch('/actions/delete-service.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `service_id=${id}`
        }).then(() => {
            fetchServices();
            fetchStats();
        });
    }
}

function deleteCategory(e) {
    const id = e.target.dataset.id;
    if (window.Modals && typeof window.Modals.showIrreversibleModal === 'function') {
        window.Modals.showIrreversibleModal(
            function onConfirm() {
                fetch('/actions/delete-category.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `category_id=${id}`
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
        fetch('/actions/delete-category.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `category_id=${id}`
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
    fetch('/actions/add-category.php', {
        method: 'POST',
        body: data
    }).then(() => {
        fetchCategories();
        fetchStats();
        form.reset();
    });
}
