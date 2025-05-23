<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../database/session.php');
  require_once(__DIR__ . '/../database/classes/service.class.php');
  require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../templates/service_card.php');

  $db = Database::getInstance();
  $services = Service::getAllServices();

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