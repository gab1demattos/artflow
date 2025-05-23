<?php function drawServiceDisplay($service, $user, $db)
{ ?>
    <?php
    require_once(__DIR__ . '/../database/classes/service.class.php');
    require_once(__DIR__ . '/../database/classes/review.class.php');

    $serviceId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($serviceId <= 0) {
        echo "<p>Invalid service ID.</p>";
        return;
    }

    // Automatically update the average rating for this service
    Service::updateAverageRating($serviceId);

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
                    // Display average rating for this service if available
                    $avgRating = isset($service['avg_rating']) ? (float)$service['avg_rating'] : 0;
                    if ($avgRating > 0) {
                        echo '<div class="service-avg-rating">';
                        echo '<p><strong>Average Rating:</strong> ' . number_format($avgRating, 1) . ' / 5.0</p>';
                        echo '<div class="stars-display">';
                        // Display filled stars based on rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($avgRating)) {
                                echo '<span class="star filled">★</span>';
                            } elseif ($i - 0.5 <= $avgRating) {
                                echo '<span class="star half-filled">&#9733;</span>';
                            } else {
                                echo '<span class="star">☆</span>';
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                    }

                    // Use Review class to get reviews
                    $reviews = Review::getReviewsByServiceId($service['id']);

                    if (count($reviews) > 0) {
                        foreach ($reviews as $review) { ?>
                            <div class="review">
                                <div class="review-header">
                                    <div class="review-user">
                                        <img class="review-user-img" src="<?= ($review->profile_image !== null && $review->profile_image !== '') ? htmlspecialchars($review->profile_image) : '/images/user_pfp/default.png' ?>" alt="Reviewer">
                                        <p><strong><?= htmlspecialchars($review->username) ?></strong></p>
                                    </div>
                                    <div class="review-rating">
                                        <?php
                                        // Show stars for this review's rating
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= floor($review->rating)) {
                                                echo '<span class="star filled">★</span>';
                                            } elseif ($i - 0.5 <= $review->rating) {
                                                echo '<span class="star half-filled">&#9733;</span>';
                                            } else {
                                                echo '<span class="star">☆</span>';
                                            }
                                        }
                                        ?>
                                        <span class="rating-value"><?= number_format($review->rating, 1) ?></span>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p><?= htmlspecialchars($review->comment) ?></p>
                                </div>
                                <div class="review-date">
                                    <small>Posted on: <?= date('M j, Y', strtotime($review->created_at)) ?></small>
                                </div>
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
                    <?php if ($user && $service['user_id'] != $user['id']): ?>
                        <a href="/pages/messages.php?user_id=<?= $service['user_id'] ?>" class="service-options" id="message">Message <?= htmlspecialchars(explode(' ', $owner['name'])[0]) ?></a>
                        <button id="payment" class="service-options">Continue to Payment</button>
                    <?php elseif (!$user): ?>
                        <button class="button filled hovering service-options">Sign Up to Message</button>
                    <?php endif; ?>
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