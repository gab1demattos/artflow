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

// Fetch min and max values for price and delivery time
$priceRange = Service::getPriceRangeByCategory($categoryId);
$deliveryRange = Service::getDeliveryRangeByCategory($categoryId);

$priceMin = $priceRange['min'] ?? 0;
$priceMax = $priceRange['max'] ?? 1000;
$deliveryMin = $deliveryRange['min'] ?? 0;
$deliveryMax = $deliveryRange['max'] ?? 30;

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
        <div class="filters-container">
            <form method="GET" action="">
                <input type="hidden" name="id" value="<?= $categoryId ?>">

                <div id="filter-container">

                    <div class="price-range-filter">
                        <div class="price-filter-content">
                            <div>
                                <label>Min Price</label>
                                <p id="min-value-filter"><?= $priceMin ?></p>
                            </div>
                            <div>
                                <label>Max Price</label>
                                <p id="max-value-filter"><?= $priceMax ?></p>
                            </div>
                        </div>
                        <div class="price-filter-slider">
                            <div class="range-fill"></div>
                            <input 
                                type="range" 
                                class="min-price-filter" 
                                min="<?= $priceMin ?>" 
                                max="<?= $priceMax ?>" 
                                value="<?= $priceMin ?>" 
                            >
                            <input 
                                type="range" 
                                class="max-price-filter" 
                                min="<?= $priceMin ?>" 
                                max="<?= $priceMax ?>" 
                                value="<?= $priceMax ?>" 
                                >
                        </div>
                    </div>

                    <div id="rating-filter">
                        <label>Minimum Rating</label>
                        <div id="filter-search-rating">
                            <div class="rating-container">
                                <div class="stars-container">
                                    <div class="stars">
                                        <i class="star-icon" data-value="1.0">★</i>
                                        <i class="star-icon" data-value="2.0">★</i>
                                        <i class="star-icon" data-value="3.0">★</i>
                                        <i class="star-icon" data-value="4.0">★</i>
                                        <i class="star-icon" data-value="5.0">★</i>
                                    </div>
                                </div>
                                <input type="hidden" id="filter-rating-value" value="0">
                                <div class="rating-display">
                                    <span id="filter-rating-text">0.0</span>/5
                                    <button id="clear-rating" title="Clear rating filter">×</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="delivery-filter">
                        <label for="delivery-max">Delivery Time (max days):</label>
                        <input type="number" name="delivery_max" id="delivery-max" placeholder="Max" min="1" max="<?= $deliveryMax ?>" value="<?= $deliveryMax ?>" step="1">
                    </div>

                    <button type="submit">Apply Filters</button>
                </div>
            </form>
        </div>

        <div id="services-list">
            <?php
            // Capture filter inputs
            $priceMin = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
            $priceMax = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
            $deliveryMax = isset($_GET['delivery_max']) ? intval($_GET['delivery_max']) : null;

            // Pagination: fetch only the services for the current page
            $offset = ($page - 1) * $servicesPerPage;
            $totalServices = Service::countServicesByCategory($categoryId);

            // Fetch filtered services
            $services = Service::getFilteredServicesByCategory($categoryId, $servicesPerPage, $offset, $priceMin, $priceMax, null, null, null, $deliveryMax);

            if ($services) {
                foreach ($services as $serviceObj) {
                    $subcatIds = $serviceObj->getSubcategoryIds();
                    $subcatIdsStr = implode(',', $subcatIds);
                    $serviceImage = $serviceObj->getFirstImage();
                    $service = $serviceObj->toArray();
                    drawServiceCard($service, $serviceImage, $subcatIdsStr);
                }
            } else {
                echo '<p>No services found matching the selected filters.</p>';
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
<link rel="stylesheet" href="/css/responsive/category-responsive.css">
<!-- Load the modular JavaScript files -->
<script src="/js/modals.js"></script>
<script src="/js/categories.js"></script>
<script src="/js/app.js"></script>
<!-- Keep script.js for backward compatibility -->
<script src="/js/script.js"></script>
