<?php
// Returns JSON: [ {id, type, image} ... ]
require_once(__DIR__ . '/../database/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

require_once(__DIR__ . '/../database/classes/category.class.php');
$categories = Category::getAllCategories();
$result = array_map(function($cat) {
    return [
        'id' => $cat->id,
        'type' => $cat->category_type,
        'image' => $cat->image
    ];
}, $categories);
echo json_encode($result);
