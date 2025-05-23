<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/classes/user.class.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../database/classes/review.class.php');
require_once(__DIR__ . '/../templates/service_card.php');

$session = Session::getInstance();
$loggedInUser = $session->getUser() ?? null;

// Get user by username
$user = User::get_user_by_username((string)$_GET['username'] ?? '') ?? null;

// If user doesn't exist, redirect to homepage
if (!$user) {
    header('Location: /');
    exit();
}

// Check if user has a bio
$hasBio = ($user->getBio() !== NULL && $user->getBio() !== '');

// Pagination for user services
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$servicesPerPage = 20; // 4 rows x 5 cards
$totalServices = Service::countServicesByUserId($user->getId());
$offset = ($page - 1) * $servicesPerPage;
$services = Service::getServicesByUserIdPaginated($user->getId(), $servicesPerPage, $offset);

// Get all services by the user (for retrieving reviews)
$allUserServices = Service::getServicesByUserId($user->getId());
$userServiceIds = array_map(function ($service) {
    return $service->id; // Access the id property directly, not via getId()
}, $allUserServices);

// Get reviews for user's services
$reviews = [];
$totalRating = 0;
$reviewCount = 0;
$averageRating = 0;

if (!empty($userServiceIds)) {
    $db = Database::getInstance();
    $placeholders = implode(',', array_fill(0, count($userServiceIds), '?'));
    $stmt = $db->prepare("
        SELECT Review.*, User.username, Service.title as service_title 
        FROM Review 
        JOIN User ON Review.user_id = User.id 
        JOIN Service ON Review.service_id = Service.id 
        WHERE Review.service_id IN ($placeholders) 
        ORDER BY Review.created_at DESC
    ");
    $stmt->execute($userServiceIds);
    $reviewsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total rating and count
    foreach ($reviewsData as $reviewData) {
        $totalRating += (float)$reviewData['rating'];
        $reviewCount++;

        $reviews[] = new Review(
            (int)$reviewData['id'],
            (int)$reviewData['user_id'],
            (int)$reviewData['service_id'],
            (float)$reviewData['rating'],
            $reviewData['comment'],
            $reviewData['created_at'],
            $reviewData['updated_at'],
            $reviewData['username'],
            $reviewData['service_title']
        );
    }

    // Calculate average rating
    $averageRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0;
}

drawHeader($loggedInUser);
?>

<main class="container">
    <div class="profile<?= !$hasBio ? ' no-bio' : '' ?>">
        <?php if ($loggedInUser && $loggedInUser['username'] === $user->getUsername()): ?>
            <button id="edit-profile-button" class="button filled orange hovering edit-profile-btn">Edit Profile</button>
        <?php elseif ($loggedInUser): ?>
            <a href="/pages/messages.php?user_id=<?= $user->getId() ?>" class="button filled yellow hovering edit-profile-btn">Send Message</a>
        <?php endif; ?>
        <div class="profile-img">
            <img src="<?= ($user->getProfileImage() !== null && $user->getProfileImage() !== '') ? htmlspecialchars($user->getProfileImage()) : '/images/user_pfp/default.png' ?>" alt="Profile Picture" />
        </div>
        <div class="info">
            <div class="name"><?= htmlspecialchars($user->getName()) ?></div>
            <div class="username">
                @<?= htmlspecialchars($user->getUsername()) ?>
                <?php if ($user->getUserType() === 'admin'): ?>
                    <div class="tag admin">admin</div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($hasBio): ?>
            <div class="bio-text"><?= htmlspecialchars($user->getBio()) ?></div>
        <?php endif; ?>
    </div>

    <div class="tabs">
        <div data-tab="listings" class="tab-trigger active">Listings</div>
        <div data-tab="reviews" class="tab-trigger">Reviews</div>
    </div>

    <div id="listings" class="tab-content active<?= empty($services) ? ' empty-state' : '' ?>">
        <?php if (empty($services)): ?>
            <img src="/images/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
        <?php else: ?>
            <div id="services-list">
                <?php foreach ($services as $serviceObj):
                    // Get subcategory IDs for this service
                    $subcatIds = $serviceObj->getSubcategoryIds();
                    $subcatIdsStr = implode(',', $subcatIds);

                    // Get first image for this service
                    $serviceImage = $serviceObj->getFirstImage();

                    // Convert service object to array for the template
                    $service = $serviceObj->toArray();

                    // Use the service card component
                    drawServiceCard($service, $serviceImage, $subcatIdsStr);
                endforeach; ?>
            </div>
            <?php
            $totalPages = ceil($totalServices / $servicesPerPage);
            if ($totalPages > 1): ?>
                <nav class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?username=<?= urlencode($user->getUsername()) ?>&page=<?= $page - 1 ?>" class="pagination-btn">&laquo; Previous</a>
                    <?php endif; ?>
                    <span class="pagination-info">Page <?= $page ?> of <?= $totalPages ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a href="?username=<?= urlencode($user->getUsername()) ?>&page=<?= $page + 1 ?>" class="pagination-btn">Next &raquo;</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div id="reviews" class="tab-content">
        <?php if (empty($reviews)): ?>
            <img src="/images/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
        <?php else: ?>
            <div class="average-rating">
                <strong>Average Rating: </strong>
                <span class="rating-value"><?= $averageRating > 0 ? htmlspecialchars($averageRating) : 'No ratings yet' ?></span>
                <div class="review-rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star<?= $i <= $averageRating ? ' filled' : '' ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-user">
                                <strong><?= htmlspecialchars($review->username) ?></strong>
                                <span class="review-date"><?= htmlspecialchars($review->created_at) ?></span>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star<?= $i <= $review->rating ? ' filled' : '' ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-body">
                            <p class="review-comment"><?= htmlspecialchars($review->comment) ?></p>
                            <p class="review-service">Service: <?= htmlspecialchars($review->service_title) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/js/profile.js"></script>

<?php
// Include the edit profile modal
include_once(__DIR__ . '/modals/edit-profile-modal.php');
// Include the change password modal
include_once(__DIR__ . '/modals/change-password.php');
// Include the irreversible action modal
include_once(__DIR__ . '/../templates/irreversible-modal.tpl.php');

drawFooter($loggedInUser);
?>