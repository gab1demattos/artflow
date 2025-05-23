<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/classes/service.class.php');
  require_once(__DIR__ . '/../database/database.php');

  $db = Database::getInstance();

  $categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

  if (!empty($categories)) {
      $services = Service::getServicesByCategories($db, $categories);
  } else {
      $search = $_GET['search'] ?? '';
      $services = empty($search) ? Service::getAllServices() : Service::searchServices($db, $search);
  }

  echo json_encode(array_map(function($service) {
    return [
        'id' => $service->id,
        'title' => $service->title,
        'description' => $service->description,
        'price' => $service->price,
        'image' => $service->getFirstImage(),
        'username' => $service->getUsername(),
        'subcategories' => implode(',', $service->getSubcategoryIds())
    ];
}, $services));
?>