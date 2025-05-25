<?php
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../templates/home.tpl.php');
require_once(__DIR__ . '/../../templates/service_card.php');
require_once(__DIR__ . '/../../database/classes/category.class.php');
require_once(__DIR__ . '/../../database/classes/service.class.php');
require_once(__DIR__ . '/../../templates/categories.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$servicesPerPage = 20;
$category = null;

if ($categoryId > 0) {
    $category = Category::getCategoryAsArrayById($categoryId);
}

if (!$category) {
    header('Location: /');
    exit();
}

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
                                <p id="min-value-filter"><?= isset($_GET['price_min']) ? $_GET['price_min'] : $priceMin ?></p>
                            </div>
                            <div>
                                <label>Max Price</label>
                                <p id="max-value-filter"><?= isset($_GET['price_max']) ? $_GET['price_max'] : $priceMax ?></p>
                            </div>
                        </div>
                        <div class="price-filter-slider">
                            <div class="range-fill"></div>
                            <input 
                                type="range" 
                                class="min-price-filter" 
                                name="price_min"
                                min="<?= $priceMin ?>" 
                                max="<?= $priceMax ?>" 
                                value="<?= isset($_GET['price_min']) ? $_GET['price_min'] : $priceMin ?>" 
                            >
                            <input 
                                type="range" 
                                class="max-price-filter" 
                                name="price_max"
                                min="<?= $priceMin ?>" 
                                max="<?= $priceMax ?>" 
                                value="<?= isset($_GET['price_max']) ? $_GET['price_max'] : $priceMax ?>" 
                            >
                        </div>
                    </div>

                    <div id="rating-filter">
                        <label id="label-rating">Minimum Rating</label>
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
                                    <input type="hidden" id="filter-rating-value" name="min_rating" value="0">
                                    <div class="rating-display">
                                        <span id="filter-rating-text">0.0</span>/5
                                        <button id="clear-rating" type="button" title="Clear rating filter">×</button>
                                    </div>
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
            $priceMin = isset($_GET['price_min']) ? floatval($_GET['price_min']) : null;
            $priceMax = isset($_GET['price_max']) ? floatval($_GET['price_max']) : null;
            $deliveryMax = isset($_GET['delivery_max']) ? intval($_GET['delivery_max']) : null;
            $minRating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : null;

            $offset = ($page - 1) * $servicesPerPage;
            $totalServices = Service::countServicesByCategory($categoryId);

            $services = Service::getFilteredServicesByCategory($categoryId, $servicesPerPage, $offset, $priceMin, $priceMax, $minRating, null, null, $deliveryMax);

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
<link rel="stylesheet" href="/css/main.css">
<script src="/js/modal/modals.js"></script>
<script src="/js/services/categories.js"></script>
<script src="/js/others/app.js"></script>
<script src="/js/others/script.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const filterRatingStars = document.querySelectorAll("#filter-search-rating .star-icon");
        const filterRatingValue = document.getElementById("filter-rating-value");
        const filterRatingText = document.getElementById("filter-rating-text");
        const clearRatingBtn = document.getElementById("clear-rating");
        let currentFilterRating = 0;

        const minPriceInput = document.querySelector(".min-price-filter");
        const maxPriceInput = document.querySelector(".max-price-filter");
        const minPriceDisplay = document.getElementById("min-value-filter");
        const maxPriceDisplay = document.getElementById("max-value-filter");
        const rangeFill = document.querySelector(".range-fill");

        function updateRangeFill() {
            if (minPriceInput && maxPriceInput && rangeFill) {
                const minVal = parseInt(minPriceInput.value);
                const maxVal = parseInt(maxPriceInput.value);
                const minRange = parseInt(minPriceInput.min);
                const maxRange = parseInt(minPriceInput.max);
                
                const leftPercent = ((minVal - minRange) / (maxRange - minRange)) * 100;
                const rightPercent = ((maxVal - minRange) / (maxRange - minRange)) * 100;
                
                rangeFill.style.left = leftPercent + "%";
                rangeFill.style.width = (rightPercent - leftPercent) + "%";
            }
        }

        if (minPriceInput && maxPriceInput) {
            minPriceInput.addEventListener("input", () => {
                if (parseFloat(minPriceInput.value) > parseFloat(maxPriceInput.value)) {
                    minPriceInput.value = maxPriceInput.value;
                }
                minPriceDisplay.textContent = minPriceInput.value;
                updateRangeFill();
            });

            maxPriceInput.addEventListener("input", () => {
                if (parseFloat(maxPriceInput.value) < parseFloat(minPriceInput.value)) {
                    maxPriceInput.value = minPriceInput.value;
                }
                maxPriceDisplay.textContent = maxPriceInput.value;
                updateRangeFill();
            });

            updateRangeFill();
        }

        function updateFilterStarDisplay(rating) {
            filterRatingStars.forEach((star) => {
                const starValue = parseFloat(star.getAttribute("data-value"));

                if (rating >= starValue) {
                    star.textContent = "★";
                    star.classList.add("active");
                    star.classList.remove("half");
                } else if (rating === starValue - 0.5) {
                    star.textContent = "★";
                    star.classList.add("active", "half");
                } else {
                    star.textContent = "★";
                    star.classList.remove("active", "half");
                }
            });

            filterRatingValue.value = rating;
            filterRatingText.textContent = rating.toFixed(1);
        }

        filterRatingStars.forEach((star) => {
            star.addEventListener("mousemove", function (e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const starValue = parseFloat(this.getAttribute("data-value"));

                if (x < rect.width / 2) {
                    updateFilterStarDisplay(starValue - 0.5);
                } else {
                    updateFilterStarDisplay(starValue);
                }
            });

            star.addEventListener("click", function (e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const starValue = parseFloat(this.getAttribute("data-value"));

                if (x < rect.width / 2) {
                    currentFilterRating = starValue - 0.5;
                } else {
                    currentFilterRating = starValue;
                }

                updateFilterStarDisplay(currentFilterRating);
            });
        });

        const filterStarsContainer = document.querySelector("#filter-search-rating .stars-container");
        if (filterStarsContainer) {
            filterStarsContainer.addEventListener("mouseleave", function () {
                updateFilterStarDisplay(currentFilterRating);
            });
        }

        if (clearRatingBtn) {
            clearRatingBtn.addEventListener("click", () => {
                currentFilterRating = 0;
                updateFilterStarDisplay(0);
            });
        }

        const filterForm = document.querySelector('.filters-container form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
            });
        }
    });
</script>
