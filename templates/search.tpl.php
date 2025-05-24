<?php 

    function drawSearchPage($db) { ?>
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
                <h3>Rating</h3>
                <div id="filter-search-rating">
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-1" checked>
                        <label for="filter-option-1"> 0</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-2" checked>
                        <label for="filter-option-2"> 1</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3"> 2</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-4" checked>
                        <label for="filter-option-4"> 3</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-5" checked>
                        <label for="filter-option-5"> 4</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-6" checked>
                        <label for="filter-option-6"> 5</label>
                    </div>
                </div>
                <?php 
                $stmt = $db->prepare('SELECT MIN(price) as min_price, MAX(price) as max_price, MAX(delivery_time) as max_delivery FROM Service');
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
                            <p id="min-value"><?php echo htmlspecialchars($priceRange['max_price']); ?></p>
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
                <h3>Delivery Time</h3>
                <div id="filter-search-delivery">
                    <label for="delivery-time">Max Delivery Time (days):</label>
                    <input type="number" id="delivery-time" min="1" step="1" max="<?php echo htmlspecialchars($priceRange['max_delivery']); ?>" value="<?php echo htmlspecialchars($priceRange['max_delivery']); ?>" step="1">
                </div>

                <script src="js/search.js"></script>
                
            </div>
            <div id="search-results" class="scrollable services-active"></div>
        </div>
    </div>
<?php } ?>


