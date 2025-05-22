<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../templates/service_card.php');
require_once(__DIR__ . '/../database/classes/category.class.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../templates/categories.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = null;

if ($categoryId > 0) {
    // Use Category class method instead of direct database query
    $category = Category::getCategoryAsArrayById($categoryId);
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
        // Fetch subcategories for this category using Category class method
        $subcategories = Category::getSubcategoriesByCategoryId($categoryId);
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
            // Fetch all services for this category using Service class method
            $services = Service::getServicesByCategory($categoryId);
            if ($services) {
                foreach ($services as $serviceObj) {
                    // Get subcategory IDs for this service
                    $subcatIds = $serviceObj->getSubcategoryIds();
                    $subcatIdsStr = implode(',', $subcatIds);
                    
                    // Get first image for this service
                    $serviceImage = $serviceObj->getFirstImage();
                    
                    // Convert service object to array for the template
                    $service = $serviceObj->toArray();
                    
                    // Use the service card component
                    drawServiceCard($service, $serviceImage, $subcatIdsStr);
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
<link rel="stylesheet" href="/css/responsive/category-responsive.css">
<!-- Load the modular JavaScript files -->
<script src="/js/modals.js"></script>
<script src="/js/categories.js"></script>
<script src="/js/app.js"></script>
<!-- Keep script.js for backward compatibility -->
<script src="/js/script.js"></script>
