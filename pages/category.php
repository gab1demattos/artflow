<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = null;
if ($categoryId > 0) {
    $stmt = $db->prepare('SELECT * FROM Category WHERE id = ?');
    $stmt->execute([$categoryId]);
    $category = $stmt->fetch();
}

if (!$category) {
    // Not found, redirect to home
    header('Location: /');
    exit();
}

drawHeader($user);
?>
<main class="container category-main-container">
    <div style="width:100%;position:relative;">
        <div class="category-page">
            <?php if (!empty($category['image'])): ?>
                <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['category_type']) ?>">
            <?php endif; ?>
            <h1 class="category-page-title">
                <?= htmlspecialchars($category['category_type']) ?>
            </h1>
        </div>
        <?php
        // Fetch subcategories for this category
        $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
        $stmt->execute([$category['id']]);
        $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if ($subcategories): ?>
        <div class="subcategory-carousel-wrapper">
            <div class="subcategory-carousel" id="subcategory-carousel">
                <?php foreach ($subcategories as $sub): ?>
                    <button class="subcategory-tag" data-subcategory-id="<?= $sub['id'] ?>">
                        <?= htmlspecialchars($sub['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="category-content">
        <!-- Future: List of services for this category -->
        <div id="services-list">
            <!-- Stub: Show all services for this category. Filtering by subcategory will be handled by JS. -->
            <div class="service-card" data-subcategory-ids="1,2">Service 1 (Subcat 1, 2)</div>
            <div class="service-card" data-subcategory-ids="2">Service 2 (Subcat 2)</div>
            <div class="service-card" data-subcategory-ids="3">Service 3 (Subcat 3)</div>
            <div class="service-card" data-subcategory-ids="1,3">Service 4 (Subcat 1, 3)</div>
            <!-- Replace with real service data in the future -->
        </div>
    </div>
</main>
<?php drawFooter($user); ?>
<link rel="stylesheet" href="/css/category.css">
<script src="/js/script.js"></script>
