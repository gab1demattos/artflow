<?php function drawSearchPage($user) { ?>
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
                <div id="filter-search-categories">
                    <h3>Categories</h3>
                    <div class="filter-option-category">
                        <input type="checkbox" id="filter-option-1" checked>
                        <label for="filter-option-1">Category 1</label>
                    </div>
                    <div class="filter-option-category">
                        <input type="checkbox" id="filter-option-2" checked>
                        <label for="filter-option-2">Category 2</label>
                    </div>
                    <div class="filter-option-category">
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3">Category 3</label>
                    </div>
                </div>
                <div id="filter-search-rating">
                    <h3>Rating</h3>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-1" checked>
                        <label for="filter-option-1">Rating 1</label>
                    </div>
                    <div class="filter-option-rating">
                        <input type="checkbox" id="filter-option-2" checked>
                        <label for="filter-option-2">Rating 2</label>
                    </div>
                    <div class="filter-option-rating">
    
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3">Rating 3</label>
                    </div>
                </div>
            </div>
            <div id="search-results" class="scrollable services-active"></div>
        </div>
    </div>
<?php } ?>