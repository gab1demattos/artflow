<?php
/**
 * Renders a service card component
 * 
 * @param array $service Array containing service details (id, title, username, etc.)
 * @param string|null $serviceImage Main image URL for the service
 * @param string $subcatIdsStr Comma-separated string of subcategory IDs
 * @return void
 */
function drawServiceCard($service, $serviceImage = null, $subcatIdsStr = '') {
    ?>
    <a href="/pages/service.php?id=<?= htmlspecialchars($service['id']) ?>" class="service-card-link">
        <div class="service-card" data-subcategory-ids="<?= htmlspecialchars($subcatIdsStr) ?>">
            <div class="pantone-image-wrapper">
                <?php if ($serviceImage): ?>
                    <img src="<?= htmlspecialchars($serviceImage) ?>" alt="Service image" class="pantone-image" />
                <?php else: ?>
                    <div class="pantone-image pantone-image-placeholder"></div>
                <?php endif; ?>
            </div>
            <div class="pantone-title"><?= htmlspecialchars($service['title']) ?></div>
            <div class="pantone-info-row">
                <span class="pantone-username"><?= htmlspecialchars($service['username']) ?></span>
                <span class="pantone-rating">★ 0.0</span>
                <span class="pantone-price"><?= htmlspecialchars($service['price']) ?>€</span>
            </div>
        </div>
    </a>
    <?php
}
?>