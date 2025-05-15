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
        <div id="services-list">
            <?php
            // Fetch all services for this category
            $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id = ?');
            $stmt->execute([$category['id']]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($services) {
                foreach ($services as $service) {
                    // Fetch subcategories for this service
                    $stmtSub = $db->prepare('SELECT subcategory_id FROM ServiceSubcategory WHERE service_id = ?');
                    $stmtSub->execute([$service['id']]);
                    $subcatIds = $stmtSub->fetchAll(PDO::FETCH_COLUMN);
                    $subcatIdsStr = implode(',', $subcatIds);
                    // Get first image (if any)
                    $serviceImages = array_filter(array_map('trim', explode(',', $service['images'] ?? '')));
                    $serviceImage = count($serviceImages) > 0 ? $serviceImages[0] : null;
            ?>
                <div class="service-card" data-subcategory-ids="<?= htmlspecialchars($subcatIdsStr) ?>">
                    <div class="pantone-image-wrapper">
                        <?php if ($serviceImage): ?>
                            <img src="<?= htmlspecialchars($serviceImage) ?>" alt="Service image" class="pantone-image" />
                        <?php else: ?>
                            <div class="pantone-image pantone-image-placeholder"></div>
                        <?php endif; ?>
                    </div>
                    <div class="pantone-title"><?= htmlspecialchars($service['title']) ?></div>
                    <div class="pantone-info-row">
                        <span class="pantone-username"><?= htmlspecialchars($service['username']) ?></span>
                        <span class="pantone-rating">★ 0.0</span>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<p>No services found in this category yet.</p>';
            }
            ?>
        </div>
    </div>
</main>
<?php drawFooter($user); ?>
<link rel="stylesheet" href="/css/category.css">
<script src="/js/script.js"></script>
