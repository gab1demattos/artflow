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
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$servicesPerPage = 20; // 4 rows x 5 cards
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
            // Pagination: fetch only the services for the current page
            $offset = ($page - 1) * $servicesPerPage;
            $totalServices = Service::countServicesByCategory($categoryId);
            $services = Service::getServicesByCategoryPaginated($categoryId, $servicesPerPage, $offset);
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
        <?php
        // Pagination controls
        $totalPages = ceil($totalServices / $servicesPerPage);
        if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php if ($page > 1): ?>
                <a href="?id=<?= $categoryId ?>&page=<?= $page - 1 ?>" class="pagination-btn">&laquo; Previous</a>
            <?php endif; ?>
            <span class="pagination-info">Page <?= $page ?> of <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?id=<?= $categoryId ?>&page=<?= $page + 1 ?>" class="pagination-btn">Next &raquo;</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
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
