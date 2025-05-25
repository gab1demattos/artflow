<?php

function drawSearchPage($db)
{ ?>
    <div class="search-content">
        <div class="search-options">
            <button id="search-services" class="active">Services</button>
            <button id="search-names">Usernames</button>
        </div>
        <div id="search-page-bar">
            <input type="text" id="search-page-input" placeholder="Search here...">
            <button id="search-page-button">Search</button>
        </div>
        <div id="search-main">
            <div id="filter-search">
                <h3>Categories</h3>
                <?php
                $stmt = $db->prepare('SELECT * FROM Category');
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $category) { ?>
                    <div class="filter-option-category">
                        <input type="checkbox" id="filter-option-<?php echo $category['id']; ?>" checked>
                        <label for="filter-option-<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_type']); ?></label>
                    </div>
                <?php } ?>
                <h3>Minimum Rating</h3>
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
                <?php
                $stmt = $db->prepare('SELECT MIN(price) as min_price, MAX(price) as max_price FROM Service');
                $stmt->execute();
                $priceRange = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="price-range-card">
                    <h3>Price</h3>
                    <div class="price-range-content">
                        <div>
                            <label>Min</label>
                            <p id="min-value"><?php echo htmlspecialchars($priceRange['min_price']); ?></p>
                        </div>
                        <div>
                            <label>Max</label>
                            <p id="max-value"><?php echo htmlspecialchars($priceRange['max_price']); ?></p>
                        </div>
                    </div>
                    <div class="price-range-slider">
                        <div class="range-fill"></div>
                        <input
                            type="range"
                            class="min-price"
                            min="<?php echo htmlspecialchars($priceRange['min_price']); ?>"
                            max="<?php echo htmlspecialchars($priceRange['max_price']); ?>"
                            value="<?php echo htmlspecialchars($priceRange['min_price']); ?>"
                            step="1">
                        <input
                            type="range"
                            class="max-price"
                            min="<?php echo htmlspecialchars($priceRange['min_price']); ?>"
                            max="<?php echo htmlspecialchars($priceRange['max_price']); ?>"
                            value="<?php echo htmlspecialchars($priceRange['max_price']); ?>"
                            step="1">
                    </div>
                </div>
                <?php
                $stmt = $db->prepare('SELECT MAX(delivery_time) as max_delivery FROM Service');
                $stmt->execute();
                $deliveryRange = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <h3>Delivery Time</h3>
                <div id="filter-search-delivery">
                    <label for="delivery-time">Max Delivery Time (days):</label>
                    <input type="number" id="delivery-time" min="1" step="1" max="<?php echo htmlspecialchars($deliveryRange['max_delivery']); ?>" value="<?php echo htmlspecialchars($deliveryRange['max_delivery']); ?>">
                </div>

                <script src="/js/services/search.js"></script>

            </div>
            <div id="search-results" class="scrollable services-active"></div>
        </div>
    </div>
<?php } ?>