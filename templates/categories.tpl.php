<?php function drawSeeMoreCategories($categories)
{ ?>
    <main class="container">
        <div id="see-more-categories-container">
            <h2>All Categories</h2>
            <div id="see-more-category-list">
                <?php foreach ($categories as $category): ?>
                    <a href="/pages/services/category.php?id=<?= $category['id'] ?>" class="see-more-category-card" style="text-decoration:none;color:inherit;" aria-label="View category <?= htmlspecialchars($category['category_type']) ?>">
                        <?php if (!empty($category['image'])): ?>
                            <div class="see-more-category-image-wrapper">
                                <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['category_type']) ?>" />
                            </div>
                        <?php endif; ?>
                        <span class="category-link" style="pointer-events:none;"><?= htmlspecialchars($category['category_type']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
<?php } ?>

<?php function drawCategory($category, $db)
{ ?>
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
            $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
            $stmt->execute([$category['id']]);
            $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div id="services-list">
                <?php
                require_once(__DIR__ . '/../database/classes/service.class.php');

                $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id = ?');
                $stmt->execute([$category['id']]);
                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($services) {
                    foreach ($services as $service) {
                        Service::updateAverageRating($service['id']);

                        $stmtUpdated = $db->prepare('SELECT avg_rating FROM Service WHERE id = ?');
                        $stmtUpdated->execute([$service['id']]);
                        $updatedService = $stmtUpdated->fetch(PDO::FETCH_ASSOC);
                        $avgRating = isset($updatedService['avg_rating']) ? (float)$updatedService['avg_rating'] : 0;
                        $formattedRating = number_format($avgRating, 1);

                        $stmtSub = $db->prepare('SELECT subcategory_id FROM ServiceSubcategory WHERE service_id = ?');
                        $stmtSub->execute([$service['id']]);
                        $subcatIds = $stmtSub->fetchAll(PDO::FETCH_COLUMN);
                        $subcatIdsStr = implode(',', $subcatIds);
                        $serviceImages = array_filter(array_map('trim', explode(',', $service['images'] ?? '')));
                        $serviceImage = count($serviceImages) > 0 ? $serviceImages[0] : null;
                ?>
                        <div class="service-card" data-subcategory-ids="<?= htmlspecialchars($subcatIdsStr) ?>">
                            <a href="/pages/services/service.php?id=<?= $service['id'] ?>" style="text-decoration: none; color: inherit;">
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
                                    <span class="pantone-rating">★ <?= $formattedRating ?></span>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>No services found in this category yet.</p>';
                }
                ?>
            </div>
        </div>
    </main>
<?php } ?>