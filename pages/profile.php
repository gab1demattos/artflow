<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

if (!$user) {
    // Redirect to home if not logged in
    header('Location: /');
    exit();
}

// Check if user has a bio
$hasBio = isset($user['bio']) && !empty(trim($user['bio']));

drawHeader($user);
?>

<main class="container">
  <div class="profile<?= !$hasBio ? ' no-bio' : '' ?>">
    <div class="profile-img">
      <img src="<?= isset($user['profile_image']) && $user['profile_image'] ? htmlspecialchars($user['profile_image']) : '/images/user_pfp/default.png' ?>" alt="Profile Picture" />
    </div>
    <div class="info">
      <div class="name"><?= htmlspecialchars($user['name']) ?></div>
      <div class="username">
        @<?= htmlspecialchars($user['username']) ?>
        <?php if ($session->isAdmin()): ?>
        <div class="tag admin">admin</div>
        <?php endif; ?>
      </div>
    </div>
    <?php if ($hasBio): ?>
    <div class="bio-text"><?= htmlspecialchars($user['bio']) ?></div>
    <?php endif; ?>
  </div>
  <div class="tabs">
    <div>Listings</div>
    <div>Reviews</div>
  </div>
  <div class='space'></div>
</main>

<?php 
drawFooter($user);
?>