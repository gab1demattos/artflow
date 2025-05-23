<?php function drawSearchPage($user) { ?>
    <div class="search-content">
        <div class="search-options">
            <button id="search-services" class="active">Services</button>
            <button id="search-names">Usernames</button>
        </div>
        <div id="search-tools">
            <div>
                <button id="search-filter"><img src="../images/logos/filter.png"></button>
            </div>
            <div id="search-page-bar">
                    <input type="text" id="search-page-input" placeholder="Search here...">
                    <button id="search-page-button">Search</button>
            </div>
        </div>
        <div id="search-results" class="scrollable services-active">
                
        </div>
    </div>
<?php } ?>