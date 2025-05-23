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
    <h1 class="admin-title">Admin Panel</h1>
    <div class="admin-stats-row">
        <div class="admin-stat-box" id="stat-users">
            <div class="stat-label">Users</div>
            <div class="stat-value" id="user-count">...</div>
        </div>
        <div class="admin-stat-box" id="stat-services">
            <div class="stat-label">Services</div>
            <div class="stat-value" id="service-count">...</div>
        </div>
        <div class="admin-stat-box" id="stat-categories">
            <div class="stat-label">Categories</div>
            <div class="stat-value" id="category-count">...</div>
        </div>
    </div>
    <div class="admin-tabs-row">
        <button class="admin-tab-btn active" data-tab="users">Manage Users</button>
        <button class="admin-tab-btn" data-tab="services">Manage Services</button>
        <button class="admin-tab-btn" data-tab="categories">Manage Categories</button>
    </div>
    <div class="admin-tab-content active" id="admin-users"></div>
    <div class="admin-tab-content" id="admin-services"></div>
    <div class="admin-tab-content" id="admin-categories"></div>
</main>
<link rel="stylesheet" href="/css/admin.css">
<script src="/js/admin.js"></script>
<?php drawFooter($user); ?>
