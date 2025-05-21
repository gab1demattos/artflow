<?php function drawServiceDisplay($service, $user, $db) { ?>
<?php
$stmtImg = $db->prepare('SELECT images FROM Service WHERE id = ?');
$stmtImg->execute([$service['id']]);
$imageRow = $stmtImg->fetch(PDO::FETCH_ASSOC); // Fetch a single row
$imagePaths = $imageRow['images']; // Extract the 'images' field
$images = explode(', ', $imagePaths); // Split the string into an array
?>
    <div id="service-display">
        <div id="service-imgs">
            <?php foreach ($images as $image) { ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="Service Image">
            <?php } ?>
        </div>
        <div id="service-img">
            <img src="<?= htmlspecialchars($images[0]) ?>" alt="Service Image">
        </div>
        <div id="service-detail">
            <div id="service-name">
                <h2><?= htmlspecialchars($service['title']) ?></h2>
                <p><?= htmlspecialchars($service['description']) ?></p>
            </div>
            <div id="service-info">
                <p id="price" class="service-info"><?= htmlspecialchars($service['price']) ?>€</p>
                <div id="service-delivery">
                    <img src="/images/logos/local_shipping.png" alt="Star Icon">
                    <p class="service-delivery" class="service-info">Delivery Time: </p>
                    <p class="service-delivery"><?= htmlspecialchars($service['delivery_time']) ?> days</p>
                </div>
                <div id="service-options">
                    <button id="message" class="service-options">Message</button>
                    <button id="payment" class="service-options">Continue to Payment</button>
                </div>
            </div>
            <div id="reviews">
                <h3>Reviews</h3>
                <?php
                $stmtReviews = $db->prepare('SELECT r.rating, r.comment, u.username FROM Review r JOIN User u ON r.user_id = u.id WHERE r.service_id = ?');
                $stmtReviews->execute([$service['id']]);
                $reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);
                if (count($reviews) > 0) {
                    foreach ($reviews as $review) { ?>
                        <div class="review">
                            <p><strong><?= htmlspecialchars($review['username']) ?>:</strong> <?= htmlspecialchars($review['comment']) ?></p>
                            <p>Rating: <?= htmlspecialchars($review['rating']) ?>/5</p>
                        </div>
                    <?php }
                } else { ?>
                    <p>No reviews yet. Be the first to leave a review!</p>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="/js/service-scroll.js"></script>
<?php } ?>