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
                <div id="filter-search-options">
                    <div class="filter-option">
                        <input type="checkbox" id="filter-option-1" checked>
                        <label for="filter-option-1">Option 1</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filter-option-2" checked>
                        <label for="filter-option-2">Option 2</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="filter-option-3" checked>
                        <label for="filter-option-3">Option 3</label>
                    </div>
                </div>
            </div>
            <div id="search-results" class="scrollable services-active"></div>
        </div>
    </div>
<?php } ?>