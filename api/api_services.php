<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/classes/service.class.php');
  require_once(__DIR__ . '/../database/database.php');

  $db = Database::getInstance();

  $categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
  $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
  $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
  $maxDeliveryTime = isset($_GET['max_delivery_time']) ? (int)$_GET['max_delivery_time'] : null;

  if (!empty($categories)) {
      $services = Service::getServicesByCategories($db, $categories, $minPrice, $maxPrice, $maxDeliveryTime);
  } else {
      $search = $_GET['search'] ?? '';
      $services = empty($search) 
          ? Service::getAllServices($minPrice, $maxPrice, $maxDeliveryTime) 
          : Service::searchServices($db, $search, $minPrice, $maxPrice, $maxDeliveryTime);
  }

  echo json_encode(array_map(function($service) {
    return [
        'id' => $service->id,
        'title' => $service->title,
        'description' => $service->description,
        'price' => $service->price,
        'image' => $service->getFirstImage(),
        'username' => $service->getUsername(),
        'subcategories' => implode(',', $service->getSubcategoryIds()),
        'delivery_time' => $service->delivery_time
    ];
  }, $services));
?>