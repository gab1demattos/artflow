<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    header('Location: /');
    exit();
}

drawHeader($user);
?>
<main class="container admin-panel-container">
    <h1>Admin Panel</h1>
    <section id="admin-stats">
        <div class="admin-stat" id="stat-users">Users: <span>...</span></div>
        <div class="admin-stat" id="stat-services">Services: <span>...</span></div>
        <div class="admin-stat" id="stat-categories">Categories: <span>...</span></div>
    </section>
    <section id="admin-users">
        <h2>Users</h2>
        <table id="admin-users-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Type</th><th>Actions</th></tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <section id="admin-services">
        <h2>Services</h2>
        <table id="admin-services-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Owner</th><th>Category</th><th>Actions</th></tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <section id="admin-categories">
        <h2>Categories</h2>
        <form id="admin-add-category-form">
            <input type="text" name="category_type" placeholder="New category name" required>
            <button type="submit">Add Category</button>
        </form>
        <table id="admin-categories-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
</main>
<link rel="stylesheet" href="/css/admin.css">
<script src="/js/admin.js"></script>
<?php drawFooter($user); ?>
