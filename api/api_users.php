<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/database.php');

  $db = Database::getInstance();
  $users = USer::searchUsers($db, $_GET['search'], 8);

  echo json_encode($artists);
?>