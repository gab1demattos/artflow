<?php
// Script to update all service ratings based on existing reviews
require_once(__DIR__ . '/database/database.php');
require_once(__DIR__ . '/database/classes/service.class.php');

$db = Database::getInstance();

try {
    echo "Updating service ratings...\n";
    
    // Get all services that have reviews
    $stmt = $db->query("SELECT DISTINCT service_id FROM Review");
    $serviceIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $updated = 0;
    foreach ($serviceIds as $serviceId) {
        $success = Service::updateAverageRating((int)$serviceId);
        if ($success) {
            $updated++;
            echo "Updated rating for service ID: $serviceId\n";
        } else {
            echo "Failed to update rating for service ID: $serviceId\n";
        }
    }
    
    echo "Successfully updated $updated service ratings.\n";
    
    // Show the updated ratings
    echo "\nUpdated service ratings:\n";
    $stmt = $db->query("SELECT id, title, avg_rating FROM Service WHERE avg_rating > 0 ORDER BY avg_rating DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($services as $service) {
        echo "Service {$service['id']}: {$service['title']} - Rating: {$service['avg_rating']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>