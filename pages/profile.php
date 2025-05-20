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

drawHeader($user);
?>

<main class="container">
  <div class="profile">
    <div class="profile-img">
      <img src="../images/user_pfp/default.png" alt="Profile Picture" />
    </div>
    <div class="info">
      <div class="name"><?= htmlspecialchars($user['name']) ?></div>
      <div class="username">@<?= htmlspecialchars($user['username']) ?></div>
      <div class="tags">
        <?php if ($session->isAdmin()): ?>
        <div class="tag admin">admin</div>
        <?php endif; ?>
      </div>
    </div>
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