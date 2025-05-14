<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$categories = getCategories();
$db = Database::getInstance();

drawHeader($user);
?>
<main class="container">
    <div id="see-more-categories-container">
        <h2>All Categories</h2>
        <div id="see-more-category-list">
            <?php foreach ($categories as $category): ?>
                <div class="see-more-category-card">
                    <?php if (!empty($category['image'])): ?>
                        <div class="see-more-category-image-wrapper">
                            <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['category_type']) ?>" />
                        </div>
                    <?php endif; ?>
                    <span class="category-link"><?= htmlspecialchars($category['category_type']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php drawFooter($user); ?>
