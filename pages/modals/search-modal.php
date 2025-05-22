<div id="search-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="search-modal">
        <div class="modal-content">
            <button class="close-modal">x</button>
            <div class="search-options">
                <button id="search-services" class="active">Services</button>
                <button id="search-names">Usernames</button>
            </div>
            <div id="search-results" class="scrollable services-active">
                <?php
                require_once(__DIR__ . '/../../database/classes/service.class.php');
                require_once(__DIR__ . '/../../database/database.php');

                $db = Database::getInstance();
                $services = Service::getAllServices();

                foreach ($services as $service) {
                    $subcatIdsStr = implode(',', $service->getSubcategoryIds());
                    $image = $service->getFirstImage() ?: '/images/service-placeholder.png';
                    $rating = $service->rating ?? '0.0';
                    $username = htmlspecialchars($service->getUsername());
                    $title = htmlspecialchars($service->title);
                    $id = htmlspecialchars($service->id);
                    echo "
                    <a href=\"/pages/service.php?id=$id\" class=\"service-card-link\">
                        <div class=\"service-card\" data-subcategory-ids=\"$subcatIdsStr\">
                            <div class=\"pantone-image-wrapper\">
                                <img src=\"$image\" alt=\"Service image\" class=\"pantone-image\" />
                            </div>
                            <div class=\"pantone-title\">$title</div>
                            <div class=\"pantone-info-row\">
                                <span class=\"pantone-username\">$username</span>
                                <span class=\"pantone-rating\">★ $rating</span>
                            </div>
                        </div>
                    </a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>