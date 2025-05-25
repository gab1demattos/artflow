<?php
require_once(__DIR__ . '/../../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../templates/home.tpl.php');
require_once(__DIR__ . '/../../templates/categories.tpl.php');
require_once(__DIR__ . '/../../templates/search.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

drawHeader($user, 'search.php');
drawSearchPage($db);
drawFooter($user); ?>
<link rel="stylesheet" href="../../css/main.css">
<script src="/js/services/rate-it.js"></script>
<script src="/js/modal/modals.js"></script>
<script src="/js/services/categories.js"></script>
<script src="/js/services/search.js"></script>
<script src="/js/others/script.js"></script>