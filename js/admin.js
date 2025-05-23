// admin.js - Handles fetching and admin actions for the admin panel

document.addEventListener('DOMContentLoaded', () => {
    // Tab switching logic
    const tabButtons = document.querySelectorAll('.admin-tab-btn');
    const tabContents = document.querySelectorAll('.admin-tab-content');
    const tabMap = {
        users: document.getElementById('admin-users'),
        services: document.getElementById('admin-services'),
        categories: document.getElementById('admin-categories')
    };
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const tab = this.getAttribute('data-tab');
            tabMap[tab].classList.add('active');
        });
    });

    fetchStats();
    renderUsersTab();
    renderServicesTab();
    renderCategoriesTab();

    // Re-render tab content on tab switch
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.getAttribute('data-tab');
            if (tab === 'users') renderUsersTab();
            if (tab === 'services') renderServicesTab();
            if (tab === 'categories') renderCategoriesTab();
        });
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
            document.querySelectorAll('.promote-btn').forEach(btn => btn.addEventListener('click', promoteUser));
            document.querySelectorAll('.ban-btn').forEach(btn => btn.addEventListener('click', banUser));
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
            document.querySelectorAll('.delete-service-btn').forEach(btn => btn.addEventListener('click', deleteService));
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
    fetch('/actions/ban-user.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${id}`
    }).then(() => fetchUsers());
}

function deleteService(e) {
    const id = e.target.dataset.id;
    fetch('/actions/delete-service.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `service_id=${id}`
    }).then(() => {
        fetchServices();
        fetchStats();
    });
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
