<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = Session::getInstance();
$loggedInUser = $session->getUser() ?? null;
$user = User::get_user_by_username((string)$_GET['username']) ?? null;

// Check if user has a bio
$hasBio = ($user->getBio() !== NULL || $user->getBio() !== '');

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
    <div>Listings</div>
    <div>Reviews</div>
  </div>
  <div class='space'></div>
</main>

<?php 
drawFooter($loggedInUser);
?>