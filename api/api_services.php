<?php

declare(strict_types=1);

require_once(__DIR__ . '/../api/api_security.php'); // Apply API security headers and CORS
require_once(__DIR__ . '/../database/security/security.php'); // Load security helpers
require_once(__DIR__ . '/../database/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../database/database.php');

$db = Database::getInstance();

$categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$maxDeliveryTime = isset($_GET['max_delivery_time']) ? (int)$_GET['max_delivery_time'] : null;
$minRating = isset($_GET['min_rating']) ? (float)$_GET['min_rating'] : 0;

if (!empty($categories)) {
    $services = Service::getServicesByCategories($db, $categories, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
} else {
    $search = $_GET['search'] ?? '';
    $services = empty($search)
        ? Service::getAllServices($minPrice, $maxPrice, $maxDeliveryTime, $minRating)
        : Service::searchServices($db, $search, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
}


if (!empty($categories)) {
    $services = Service::getServicesByCategories($db, $categories, $minPrice, $maxPrice);
} else {
    $search = $_GET['search'] ?? '';
    $services = empty($search) ? Service::getAllServices($minPrice, $maxPrice) : Service::searchServices($db, $search, $minPrice, $maxPrice);
}

echo json_encode(array_map(function ($service) {
    return [
        'id' => $service->id,
        'title' => $service->title,
        'description' => $service->description,
        'price' => $service->price,
        'image' => $service->getFirstImage(),
        'username' => $service->getUsername(),
        'subcategories' => implode(',', $service->getSubcategoryIds()),
        'delivery_time' => $service->delivery_time,
        'rating' => $service->avg_rating
    ];
}, $services));
