<?php
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../templates/home.tpl.php');
require_once(__DIR__ . '/../../database/classes/category.class.php');
require_once(__DIR__ . '/../../templates/categories.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
// Use the Category class method instead of the function
$categories = Category::getCategories();

// Use absolute path for CSS files
?>
<link rel="stylesheet" href="/css/main.css">
<?php
drawHeader($user);
drawSeeMoreCategories($categories);
drawFooter($user); 
?>
