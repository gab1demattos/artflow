<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../templates/categories.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = null;
if ($categoryId > 0) {
    $stmt = $db->prepare('SELECT * FROM Category WHERE id = ?');
    $stmt->execute([$categoryId]);
    $category = $stmt->fetch();
}

if (!$category) {
    // Not found, redirect to home
    header('Location: /');
    exit();
}

drawHeader($user);
drawCategory($category, $db);
drawFooter($user); ?>
<link rel="stylesheet" href="/css/category.css">
<script src="/js/script.js"></script>
