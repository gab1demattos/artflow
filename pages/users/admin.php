<?php
require_once(__DIR__ . '../../database/security/security_bootstrap.php');
require_once(__DIR__ . '../../database/session.php');
require_once(__DIR__ . '../../templates/home.tpl.php');

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
    <div class="stat-row">
        <div class="stat-box stat-box--yellow" id="stat-users">
            <div class="stat-label stat-label--yellow">Users</div>
            <div class="stat-value stat-value--yellow" id="user-count">...</div>
        </div>
        <div class="stat-box stat-box--yellow" id="stat-services">
            <div class="stat-label stat-label--yellow">Services</div>
            <div class="stat-value stat-value--yellow" id="service-count">...</div>
        </div>
        <div class="stat-box stat-box--yellow" id="stat-categories">
            <div class="stat-label stat-label--yellow">Categories</div>
            <div class="stat-value stat-value--yellow" id="category-count">...</div>
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
                    <form id="category-form" class="form" action="../../actions/adminpanel/add-category.php" method="post" enctype="multipart/form-data">
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
<?php include_once(__DIR__ . '../../templates/irreversible-modal.tpl.php'); ?>
<link rel="stylesheet" href="../../css/main.css">
<script src="../../js/modal/modals.js"></script>
<script src="../../js/users/admin.js"></script>
<script src="../../js/users/admin-responsive.js"></script>
<?php drawFooter($user); ?>