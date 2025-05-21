<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/classes/category.class.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
// Use the Category class method instead of the function
$categories = Category::getCategories();

drawHeader($user);
?>
<main class="container">
    <div id="see-more-categories-container">
        <h2>All Categories</h2>
        <div id="see-more-category-list">
            <?php foreach ($categories as $category): ?>
                <a href="/pages/category.php?id=<?= $category['id'] ?>" class="see-more-category-card" style="text-decoration:none;color:inherit;" aria-label="View category <?= htmlspecialchars($category['category_type']) ?>">
                    <?php if (!empty($category['image'])): ?>
                        <div class="see-more-category-image-wrapper">
                            <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['category_type']) ?>" />
                        </div>
                    <?php endif; ?>
                    <span class="category-link" style="pointer-events:none;"><?= htmlspecialchars($category['category_type']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php drawFooter($user); ?>
