<?php function drawSearchPage($user) { ?>
    <div class="search-content">
        <button class="close-modal">x</button>
        <div class="search-options">
            <button id="search-services" class="active">Services</button>
            <button id="search-names">Usernames</button>
        </div>
        <div id="search-results" class="scrollable services-active">
                
        </div>
    </div>
<?php } ?>