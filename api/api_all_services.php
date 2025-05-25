<?php

declare(strict_types=1);
require_once(__DIR__ . '/../api/api_security.php'); 
require_once(__DIR__ . '/../database/security/security.php');
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../templates/service_card.php');

$db = Database::getInstance();
$services = Service::getAllServices();

echo json_encode(array_map(function($service) {
    // make sure we're using the latest rating
    Service::updateAverageRating($service->id);
    
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT avg_rating FROM Service WHERE id = ?');
    $stmt->execute([$service->id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_rating = $result ? (float)$result['avg_rating'] : 0;
    
    return [
        'id' => $service->id,
        'title' => $service->title,
        'description' => $service->description,
        'price' => $service->price,
        'delivery_time' => $service->delivery_time,
        'image' => $service->getFirstImage(),
        'username' => $service->getUsername(),
        'subcategories' => implode(',', $service->getSubcategoryIds()),
        'rating' => $current_rating
    ];
}, $services));
?>

