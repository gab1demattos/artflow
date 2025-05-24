<?php
require_once(__DIR__ . '/../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/category.class.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

drawHeader($user);
drawTitle();
drawCategories();
drawInfo();
drawFooter($user);
