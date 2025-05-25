<?php
require_once(__DIR__ . '/../../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    header('Location: /');
    exit();
}

drawHeader($user);
?>
<main class="container activity-container">
    <h1 class="activity-title">Activity</h1>
    <div class="activity-tabs">
        <button class="activity-tab active" data-tab="your-orders">Your Orders</button>
        <button class="activity-tab" data-tab="orders-from-others">Orders From Others</button>
    </div>
    <div class="activity-tab-content active" id="your-orders">
        <!-- Orders will be loaded dynamically by JS -->
    </div>
    <div class="activity-tab-content" id="orders-from-others">
        <!-- Orders will be loaded dynamically by JS -->
    </div>
</main>
<script src="../../js/others/activity.js"></script>
<?php drawFooter($user); ?>