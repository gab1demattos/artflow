<?php
require_once(__DIR__ . '/../database/security_bootstrap.php');
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php'); // Added this line to include the Database class
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../templates/categories.tpl.php');
require_once(__DIR__ . '/../templates/search.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
$db = Database::getInstance();

// Pass the current page name to drawHeader
drawHeader($user, 'search.php');
drawSearchPage($db); ?>
<link rel="stylesheet" href="/css/search.css">
<!-- Load the modular JavaScript files -->
<script src="/js/modals.js"></script>
<script src="/js/categories.js"></script>
<script src="/js/search.js"></script>
<!-- Keep script.js for backward compatibility -->
<script src="/js/script.js"></script>