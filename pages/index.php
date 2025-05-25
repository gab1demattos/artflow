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
?>
<link rel="stylesheet" href="../css/flow-animation.css">
<script src="../js/flow-animation.js"></script>
<div class="flow-container">
    <svg class="flow-svg" viewBox="0 0 100 1000" preserveAspectRatio="none">
        <defs>
            <linearGradient id="flow-gradient" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="0" y2="1000">
                <stop offset="0%" stop-color="#9D6B99" />
                <stop offset="50%" stop-color="#E89B7B" />
                <stop offset="100%" stop-color="#D6BE55" />
            </linearGradient>
        </defs>
        <path class="flow-path" d="M30,0 C60,200 20,400 70,600 C30,800 60,1000 30,1000" />
    </svg>
</div>