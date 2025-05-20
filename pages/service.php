<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
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
?>
<link rel="stylesheet" href="/css/category.css">
<script src="/js/script.js"></script>
