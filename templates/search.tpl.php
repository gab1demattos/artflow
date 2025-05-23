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
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3"> 3</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3"> 4</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3"> 5</label>
                    </div>
                </div>
                <?php 
                $stmt = $db->prepare('SELECT MIN(price) as min_price, MAX(price) as max_price FROM Service');
                $stmt->execute();
                $priceRange = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <h3>Price</h3>
                <div id="filter-search-price">
                    <input type="range" id="min-price" min="<?php echo htmlspecialchars($priceRange['min_price']); ?>" max="<?php echo htmlspecialchars($priceRange['max_price']); ?>" value="<?php echo htmlspecialchars($priceRange['min_price']); ?>" step="1" oninput="updatePriceRange()">
                    <input type="range" id="max-price" min="<?php echo htmlspecialchars($priceRange['min_price']); ?>" max="<?php echo htmlspecialchars($priceRange['max_price']); ?>" value="<?php echo htmlspecialchars($priceRange['max_price']); ?>" step="1" oninput="updatePriceRange()">
                    <div id="price-range-values">
                        <span id="min-price-value"><?php echo htmlspecialchars($priceRange['min_price']); ?></span> - 
                        <span id="max-price-value"><?php echo htmlspecialchars($priceRange['max_price']); ?></span>
                    </div>
                </div>

                <script>
                    function updatePriceRange() {
                        const minPrice = document.getElementById('min-price').value;
                        const maxPrice = document.getElementById('max-price').value;
                        document.getElementById('min-price-value').textContent = minPrice;
                        document.getElementById('max-price-value').textContent = maxPrice;
                    }
                </script>
                
            </div>
            <div id="search-results" class="scrollable services-active"></div>
        </div>
    </div>
<?php } ?>