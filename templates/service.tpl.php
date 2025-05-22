<?php function drawServiceDisplay($service, $user, $db) { ?>
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
                <p><?= htmlspecialchars($owner['name'])?></p>
                <p>@<?= htmlspecialchars($owner['username'])?></p>
            </div>
            <div id="service-info">
                <p id="price" class="service-info"><?= htmlspecialchars($service['price']) ?>€</p>
                <div id="service-delivery">
                    <img src="/images/logos/local_shipping.png" alt="Star Icon">
                    <p class="service-delivery" class="service-info">Delivery Time: </p>
                    <p class="service-delivery"><?= htmlspecialchars($service['delivery_time']) ?> days</p>
                </div>
                <div id="service-options">
                    <button id="message" class="service-options">Message <?= htmlspecialchars($owner['name']) ?></button>
                    <button id="payment" class="service-options">Continue to Payment</button>
                </div>
            </div>
        
        </div>
    </div>
    <script src="/js/service-scroll.js"></script>
    <!-- Requirements Modal -->
    <div id="requirements-modal-overlay" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-content">
                <h2>Submit Requirements</h2>
                <textarea id="requirements-textarea" placeholder="Describe what you need to get started..." rows="5" style="width:100%;margin-bottom:1em;"></textarea>
                <div class="button-container">
                    <button id="requirements-continue" class="button filled long hovering">Continue</button>
                    <button id="close-requirements-modal" class="button outline">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Modal -->
    <div id="payment-modal-overlay" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-content">
                <h2>Payment</h2>
                <form id="payment-form" autocomplete="off">
                    <label>Card Number
                        <input type="text" name="card" maxlength="19" placeholder="1234 5678 9012 3456" required pattern="[0-9 ]+">
                    </label>
                    <label>Name on Card
                        <input type="text" name="name" maxlength="40" placeholder="Full Name" required>
                    </label>
                    <div style="display:flex;gap:1em;">
                        <label style="flex:1;">Exp. Date
                            <input type="text" name="exp" maxlength="5" placeholder="MM/YY" required pattern="[0-9/]+">
                        </label>
                        <label style="flex:1;">CVV
                            <input type="text" name="cvv" maxlength="4" placeholder="123" required pattern="[0-9]+">
                        </label>
                    </div>
                    <div class="button-container">
                        <button id="payment-confirm" class="button filled long hovering">Confirm Payment</button>
                        <button type="button" id="close-payment-modal" class="button outline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/js/checkout.js"></script>
<?php } ?>