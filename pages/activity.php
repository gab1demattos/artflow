<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

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
        <!-- Placeholder: Replace with dynamic order data -->
        <div class="order-card">
            <div class="order-header">
                <span class="order-title">Logo Design Package</span>
                <span class="order-status completed">Completed</span>
            </div>
            <div class="order-details">
                <div><strong>Seller:</strong> John Doe (@johndoe)</div>
                <div><strong>Delivery:</strong> 5 days</div>
                <div><strong>Requirements:</strong> Company name, color preferences</div>
                <div><strong>Total:</strong> 50€</div>
                <div><strong>Date:</strong> 2025-05-20</div>
            </div>
        </div>
        <div class="order-card">
            <div class="order-header">
                <span class="order-title">Digital Portrait</span>
                <span class="order-status in-progress">In Progress</span>
            </div>
            <div class="order-details">
                <div><strong>Seller:</strong> Jane Smith (@janesmith)</div>
                <div><strong>Delivery:</strong> 3 days</div>
                <div><strong>Requirements:</strong> Photo reference</div>
                <div><strong>Total:</strong> 30€</div>
                <div><strong>Date:</strong> 2025-05-21</div>
            </div>
        </div>
    </div>
    <div class="activity-tab-content" id="orders-from-others">
        <!-- Placeholder: Replace with dynamic order data -->
        <div class="order-card">
            <div class="order-header">
                <span class="order-title">Brand Identity Package</span>
                <span class="order-status awaiting">Awaiting Requirements</span>
            </div>
            <div class="order-details">
                <div><strong>Buyer:</strong> Alice Brown (@aliceb)</div>
                <div><strong>Delivery:</strong> 7 days</div>
                <div><strong>Requirements:</strong> --</div>
                <div><strong>Total:</strong> 120€</div>
                <div><strong>Date:</strong> 2025-05-22</div>
            </div>
        </div>
    </div>
</main>
<link rel="stylesheet" href="/css/activity.css">
<script src="/js/activity.js"></script>
<?php drawFooter($user); ?>
