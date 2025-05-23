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
    <div class="admin-tab-content" id="admin-categories">
        <button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button>
        <div id="admin-categories-table"></div>
    </div>
    <div id="category-modal-overlay" class="modal-overlay hidden">
        <div class="modal" id="category-modal">
            <div class="modal-content">
                <div class="form-container">
                    <h2>Add Category</h2>
                    <form id="category-form" class="form" action="/actions/create-category.php" method="post" enctype="multipart/form-data">
                        <input type="text" name="category_name" placeholder="Category name" required>
                        <input type="file" name="category_image" accept="image/*">
                        <input type="text" name="subcategories" placeholder="Subcategories (comma separated)">
                        <div class="button-container">
                            <button type="submit" class="button filled classic">Create</button>
                            <button type="button" id="close-category-modal" class="button outline">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once(__DIR__ . '/../templates/irreversible-modal.tpl.php'); ?>
<link rel="stylesheet" href="/css/admin.css">
<link rel="stylesheet" href="/css/modals.css">
<script src="/js/modals.js"></script>
<script src="/js/admin.js"></script>
<?php drawFooter($user); ?>
