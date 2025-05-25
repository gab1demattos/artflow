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
    $imageRow = $stmtImg->fetch(PDO::FETCH_ASSOC); 
    $imagePaths = $imageRow['images'];
    $images = array_filter(array_map('trim', explode(',', $imagePaths)));

    $stmtOwner = $db->prepare('SELECT u.name, u.username, u.profile_image FROM User u JOIN Service s ON u.id = s.user_id WHERE s.id = ?');
    $stmtOwner->execute([$service['id']]);
    $owner = $stmtOwner->fetch(PDO::FETCH_ASSOC);
    ?>
    <link rel="stylesheet" href="/css/main.css">
    <div id="service-display">
        <div id="service-main">
            <div id="images-service">
                <?php if (count($images) > 1): ?>
                <div id="service-imgs">
                    <?php foreach ($images as $image) { ?>
                        <img class="service-imgs" src="<?= htmlspecialchars($image) ?>" alt="Service Image Thumbnail">
                    <?php } ?>
                </div>
                <?php endif; ?>
                <div id="main-image">
                    <img class="main-image" src="<?= htmlspecialchars($images[0]) ?>" alt="Service Image">
                </div>
            </div>
            <div id="service-details">
                <div id="reviews">
                    <h3>Reviews</h3>
                    <?php
                    $avgRating = isset($service['avg_rating']) ? (float)$service['avg_rating'] : 0;
                    if ($avgRating > 0) {
                        echo '<div class="service-avg-rating">';
                        echo '<p><strong>Average Rating:</strong> ' . number_format($avgRating, 1) . ' / 5.0</p>';
                        echo '<div class="stars-display">';
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
                <a href="/pages/users/profile.php?username=<?= urlencode($owner['username']) ?>" style="display: flex; align-items: center; gap: 0.5em; text-decoration: none; color: inherit;">
                    <img src="<?= ($owner['profile_image'] !== null && $owner['profile_image'] !== '') ? htmlspecialchars($owner['profile_image']) : '/images/user_pfp/default.png' ?>" alt="User Icon">
                    <div style="display: flex; flex-direction: row; align-items: center; gap: 0.5em;">
                        <p style="margin: 0; font-weight: 600;"><?= htmlspecialchars($owner['name']) ?></p>
                        <p style="margin: 0; opacity: 0.7;">@<?= htmlspecialchars($owner['username']) ?></p>
                    </div>
                </a>
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
                        <a href="/pages/users/messages.php?user_id=<?= $service['user_id'] ?>" class="service-options" id="message">Message <?= htmlspecialchars(explode(' ', $owner['name'])[0]) ?></a>
                        <button id="payment" class="service-options">Continue to Payment</button>
                    <?php elseif (!$user): ?>
    <div class="service-warning">Sign up to message and order</div>
                    <?php elseif ($user && $service['user_id'] == $user['id']): ?>
    <button id="edit-service-btn"
        data-service-id="<?= $service['id'] ?>"
        data-title="<?= htmlspecialchars($service['title'], ENT_QUOTES) ?>"
        data-description="<?= htmlspecialchars($service['description'], ENT_QUOTES) ?>"
        data-category="<?= $service['category_id'] ?? '' ?>"
        data-subcategory="<?= $service['subcategory_id'] ?? '' ?>"
        data-price="<?= htmlspecialchars($service['price']) ?>"
        data-delivery="<?= htmlspecialchars($service['delivery_time']) ?>"
        class="button filled hovering service-options">
        Edit Service
    </button>
    <button id="delete-service-btn" class="delete-service-btn" type="button">Delete Service</button>
                    <?php endif; ?>
                </div>
            </div>
            <div id="service-description">
                 <br>
                <p><?= htmlspecialchars($service['description']) ?></p>
            </div>
        </div>
    </div>
    <script src="/js/services/service-scroll.js"></script>
    <script src="/js/modal/edit-service-modal.js"></script>
    <?php include __DIR__ . '/../pages/modals/requirements-modal.php'; ?>
    <?php include __DIR__ . '/../pages/modals/payment-modal.php'; ?>
    <?php include __DIR__ . '/../pages/modals/thankyou-modal.php'; ?>
    <?php include __DIR__ . '/../pages/modals/edit-service-modal.php'; ?>
    <?php include __DIR__ . '/irreversible-modal.tpl.php'; ?>
    <script src="/js/modal/modals.js"></script>
    <script src="/js/services/checkout.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('delete-service-btn');
    if (deleteBtn && window.Modals && typeof window.Modals.showIrreversibleModal === 'function') {
        deleteBtn.addEventListener('click', function() {
            window.Modals.showIrreversibleModal(
                function onConfirm() {
                    fetch('/actions/adminpanel/delete-service.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'service_id=<?= $service['id'] ?>'
                    })
                    .then(r => r.json())
                    .then(result => {
                        if (result.success) {
                            window.location.href = '/pages/users/profile.php?username=<?= urlencode($owner['username']) ?>';
                        } else {
                            alert(result.error || 'Failed to delete service.');
                        }
                    })
                    .catch(() => alert('Failed to delete service.'));
                },
                function onCancel() {
                    // Do nothing
                }
            );
        });
    }
});
</script>
<?php } ?>