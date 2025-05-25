<?php
require_once(__DIR__ . '/../../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../templates/home.tpl.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/classes/service.class.php');
require_once(__DIR__ . '/../../database/classes/review.class.php');
require_once(__DIR__ . '/../../templates/service_card.php');

$session = Session::getInstance();
$loggedInUser = $session->getUser() ?? null;

$user = User::get_user_by_username((string)$_GET['username'] ?? '') ?? null;

if (!$user) {
    header('Location: /');
    exit();
}

$hasBio = ($user->getBio() !== NULL && $user->getBio() !== '');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$servicesPerPage = 20;
$totalServices = Service::countServicesByUserId($user->getId());
$offset = ($page - 1) * $servicesPerPage;
$services = Service::getServicesByUserIdPaginated($user->getId(), $servicesPerPage, $offset);

$allUserServices = Service::getServicesByUserId($user->getId());
$userServiceIds = array_map(function ($service) {
    return $service->id; 
}, $allUserServices);

$reviewsData = Review::getReviewsByServiceIds($userServiceIds);
$reviews = $reviewsData['reviews'];
$averageRating = $reviewsData['averageRating'];

drawHeader($loggedInUser);
?>

<main class="container">
    <div class="profile<?= !$hasBio ? ' no-bio' : '' ?>">
        <?php if ($loggedInUser && $loggedInUser['username'] === $user->getUsername()): ?>
            <button id="edit-profile-button" class="button filled orange hovering edit-profile-btn">Edit Profile</button>
        <?php elseif ($loggedInUser): ?>
            <a href="../../pages/users/messages.php?user_id=<?= $user->getId() ?>" class="button filled yellow hovering edit-profile-btn">Send Message</a>
        <?php endif; ?>
        <div class="profile-img">
            <img src="<?= ($user->getProfileImage() !== null && $user->getProfileImage() !== '') ? htmlspecialchars($user->getProfileImage()) : '../../images/user_pfp/default.png' ?>" alt="Profile Picture" />
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
            <img src="../../images/profile/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
        <?php else: ?>
            <div id="services-list">
                <?php foreach ($services as $serviceObj):
                    $subcatIds = $serviceObj->getSubcategoryIds();
                    $subcatIdsStr = implode(',', $subcatIds);

                    $serviceImage = $serviceObj->getFirstImage();

                    $service = $serviceObj->toArray();

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

    <div id="reviews" class="tab-content<?= empty($reviews) ? ' empty-state' : '' ?>">
        <?php if (empty($reviews)): ?>
            <img src="../../images/profile/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
        <?php else: ?>
            <div class="average-rating">
                <strong>Average Rating: </strong>
                <span class="rating-value"><?= $averageRating > 0 ? htmlspecialchars(number_format($averageRating, 1)) : 'No ratings yet' ?></span>
                <div class="review-rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++):
                        if ($averageRating >= $i) {
                            echo '<span class="star filled">★</span>';
                        } else if ($averageRating >= $i - 0.5) {
                            echo '<span class="star half-filled">&#9733;</span>';
                        } else {
                            echo '<span class="star">☆</span>';
                        }
                    endfor; ?>
                </div>
            </div>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-user">
                                <img class="review-user-img" src="<?= ($review->profile_image !== null && $review->profile_image !== '') ? htmlspecialchars($review->profile_image) : '/images/user_pfp/default.png' ?>" alt="Reviewer">
                                <div class="review-user-details">
                                    <strong><?= htmlspecialchars($review->username) ?></strong>
                                    <span class="review-date"><?= htmlspecialchars($review->created_at) ?></span>
                                </div>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++):
                                    if ($review->rating >= $i) {
                                        echo '<span class="star filled">★</span>';
                                    } else if ($review->rating >= $i - 0.5) {
                                        echo '<span class="star half-filled">&#9733;</span>';
                                    } else {
                                        echo '<span class="star">☆</span>';
                                    }
                                endfor; ?>
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

<script src="../../js/users/profile.js"></script>

<?php
include_once(__DIR__ . '/../modals/edit-profile-modal.php');
include_once(__DIR__ . '/../modals/change-password.php');
include_once(__DIR__ . '/../../templates/irreversible-modal.tpl.php');

drawFooter($loggedInUser);
?>