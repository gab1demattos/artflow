<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php'); // Added this line to include the Database class
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../templates/categories.tpl.php');
require_once(__DIR__ . '/../templates/service.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

$serviceId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.id = ?');
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$service) {
    // Not found, redirect to home
    header('Location: /');
    exit();
}

drawHeader($user);
drawServiceDisplay($service, $user, $db);
?>
<link rel="stylesheet" href="/css/category.css">
<!-- Load the modular JavaScript files -->
<script src="/js/modals.js"></script>
<script src="/js/categories.js"></script>
<script src="/js/app.js"></script>
<!-- Keep script.js for backward compatibility -->
<script src="/js/script.js"></script>
