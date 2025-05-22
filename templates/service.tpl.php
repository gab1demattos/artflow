<?php function drawServiceDisplay($service, $user, $db)
{ ?>
    <?php
    $serviceId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($serviceId <= 0) {
        echo "<p>Invalid service ID.</p>";
        return;
    }

    $stmtService = $db->prepare('SELECT * FROM Service WHERE id = ?');
    $stmtService->execute([$serviceId]);
    $service = $stmtService->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo "<p>Service not found.</p>";
        return;
    }

    $stmtImg = $db->prepare('SELECT images FROM Service WHERE id = ?');
    $stmtImg->execute([$service['id']]);
    $imageRow = $stmtImg->fetch(PDO::FETCH_ASSOC); // Fetch a single row
    $imagePaths = $imageRow['images']; // Extract the 'images' field
    $images = explode(', ', $imagePaths); // Split the string into an array

    $stmtOwner = $db->prepare('SELECT u.name, u.username FROM User u JOIN Service s ON u.id = s.user_id WHERE s.id = ?');
    $stmtOwner->execute([$service['id']]);
    $owner = $stmtOwner->fetch(PDO::FETCH_ASSOC);
    ?>
    <link rel="stylesheet" href="/css/responsive/service-responsive.css">
    <div id="service-display">
        <div id="service-main">
            <div id="images-service">
                <div id="service-imgs">
                    <?php foreach ($images as $image) { ?>
                        <img class="service-imgs" src="<?= htmlspecialchars($image) ?>" alt="Service Image">
                    <?php } ?>
                </div>
                <div id="main-image"><img class="main-image" src="<?= htmlspecialchars($images[0]) ?>" alt="Service Image"></div>
            </div>
            <div id="service-details">
                <p><?= htmlspecialchars($service['description']) ?></p>
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
        <div id="service-checkout">
            <h2><?= htmlspecialchars($service['title']) ?></h2>
            <div id="owner-info">
                <img src="../images/logos/avatar.png" alt="User Icon">
                <p><?= htmlspecialchars($owner['name']) ?></p>
                <p>@<?= htmlspecialchars($owner['username']) ?></p>
            </div>
            <div id="service-info">
                <p id="price" class="service-info"><?= htmlspecialchars($service['price']) ?>€</p>
                <div id="service-delivery">
                    <img src="/images/logos/local_shipping.png" alt="Star Icon">
                    <p class="service-delivery" class="service-info">Delivery Time: </p>
                    <p class="service-delivery"><?= htmlspecialchars($service['delivery_time']) ?> days</p>
                </div>
                <div id="service-options">
                    <?php if ($user): ?>
                        <a href="/pages/messages.php?user_id=<?= $service['user_id'] ?>" class="service-options" id="message">Message <?=  htmlspecialchars(explode(' ', $owner['name'])[0]) ?></a>
                    <?php else: ?>
                        <button class="button filled hovering service-options">Sign Up to Message</button>
                    <?php endif; ?>
                    <button id="payment" class="service-options">Continue to Payment</button>
                </div>
            </div>

        </div>
    </div>
    <script src="/js/service-scroll.js"></script>

    <?php include __DIR__ . '/../pages/modals/requirements-modal.php'; ?>
    <?php include __DIR__ . '/../pages/modals/payment-modal.php'; ?>
    <?php include __DIR__ . '/../pages/modals/thankyou-modal.php'; ?>
    <script src="/js/checkout.js"></script>
<?php } ?>