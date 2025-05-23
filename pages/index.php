<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/category.class.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

// Check if account was deleted successfully
$deleted = isset($_GET['deleted']) && $_GET['deleted'] === 'success';

drawHeader($user);

// Display success message if account was deleted
if ($deleted) {
    echo '<div class="success-message" style="background-color: #4CAF50; color: white; padding: 15px; margin: 20px; border-radius: 5px; text-align: center;">
            <p style="margin: 0; font-size: 16px;">Your account has been successfully deleted.</p>
          </div>';
}

drawTitle();
drawCategories();
drawInfo();
drawFooter($user);
