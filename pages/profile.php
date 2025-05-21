<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../templates/service_card.php');

$session = Session::getInstance();
$loggedInUser = $session->getUser() ?? null;
$user = User::get_user_by_username((string)$_GET['username']) ?? null;

// Check if user has a bio
$hasBio = ($user->getBio() !== NULL && $user->getBio() !== '');

// Get user's services
$services = Service::getServicesByUserId($user->getId());

drawHeader($loggedInUser);
?>

<main class="container">
  <div class="profile<?= !$hasBio ? ' no-bio' : '' ?>">
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
  
  <div id="listings" class="tab-content active" style="align-items: flex-start !important; justify-content: flex-start !important;">
    <?php if (empty($services)): ?>
        <img src="/images/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
    <?php else: ?>
      <div id="services-list" style="justify-content: flex-start !important; align-items: flex-start !important;">
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
    <?php endif; ?>
  </div>
  
  <div id="reviews" class="tab-content">
    <img src="/images/nothing-to-see-here.png" alt="Nothing to see here!" class="nothing-img" />
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabTriggers = document.querySelectorAll('.tab-trigger');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabTriggers.forEach(trigger => {
    trigger.addEventListener('click', function() {
      // Remove active class from all triggers and contents
      tabTriggers.forEach(t => t.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));
      
      // Add active class to clicked trigger
      this.classList.add('active');
      
      // Show corresponding tab content
      const tabId = this.getAttribute('data-tab');
      document.getElementById(tabId).classList.add('active');
    });
  });
});
</script>

<?php 
drawFooter($loggedInUser);
?>