<?php 
require_once(__DIR__ . '/includes/session.php');
require_once(__DIR__ . '/includes/categories.php');
require_once(__DIR__ . '/templates/home.tpl.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;

drawHeader($user);
drawTitle();
drawCategories();   
drawInfo();
drawFooter($user);

?>
