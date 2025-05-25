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
$search = $_GET['search'] ?? '';

// Debug logging
error_log("API called with params: search='$search', categories=" . implode(',', $categories) . ", minPrice=$minPrice, maxPrice=$maxPrice, maxDeliveryTime=$maxDeliveryTime, minRating=$minRating");

// Handle different combinations of search and category filtering
if (!empty($search)) {
    // If there's a search term, we need to handle it differently based on categories
    if (!empty($categories)) {
        // Search within specific categories with filters
        $services = Service::searchServicesInCategories($db, $search, $categories, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
    } else {
        // Search across all services with filters
        $services = Service::searchServices($db, $search, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
    }
} else {
    // No search term - just apply category and other filters
    if (!empty($categories)) {
        $services = Service::getServicesByCategories($db, $categories, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
    } else {
        $services = Service::getAllServices($minPrice, $maxPrice, $maxDeliveryTime, $minRating);
    }
}

error_log("API returning " . count($services) . " services after filtering");

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
