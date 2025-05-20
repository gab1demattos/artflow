<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../templates/categories.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$categories = getCategories();
$db = Database::getInstance();

drawHeader($user);
drawSeeMoreCategories($categories);
drawFooter($user); 
?>
