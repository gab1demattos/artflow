<?php
// actions/edit-service.php
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/session.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated.']);
    exit;
}

$serviceId = intval($_POST['service_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category = intval($_POST['category'] ?? 0);
$subcategory = intval($_POST['subcategory'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$delivery = intval($_POST['delivery_time'] ?? 0);

if ($serviceId <= 0 || !$title || !$description || $category <= 0 || $price <= 0 || $delivery <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid fields.']);
    exit;
}

$db = Database::getInstance();
$stmt = $db->prepare('SELECT user_id, images FROM Service WHERE id = ?');
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service || $service['user_id'] != $user['id']) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized.']);
    exit;
}

// Handle image uploads (optional: keep old images if none uploaded)
$images = $service['images'];
if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = __DIR__ . '/../images/services/';
    $uploaded = [];
    foreach ($_FILES['images']['tmp_name'] as $idx => $tmpName) {
        if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['images']['name'][$idx], PATHINFO_EXTENSION);
            $filename = uniqid('service_', true) . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($tmpName, $dest)) {
                $uploaded[] = '/images/services/' . $filename;
            }
        }
    }
    if ($uploaded) {
        $images = implode(',', $uploaded);
    }
}

// Update Service main fields (no subcategory_id column!)
$update = $db->prepare('UPDATE Service SET title = ?, description = ?, category_id = ?, price = ?, delivery_time = ?, images = ? WHERE id = ?');
$update->execute([$title, $description, $category, $price, $delivery, $images, $serviceId]);

// Update subcategories (ServiceSubcategory table)
if (isset($_POST['subcategories']) && is_array($_POST['subcategories'])) {
    // Remove old subcategories
    $stmt = $db->prepare('DELETE FROM ServiceSubcategory WHERE service_id = ?');
    $stmt->execute([$serviceId]);
    // Insert new subcategories
    foreach ($_POST['subcategories'] as $subcatId) {
        if (is_numeric($subcatId)) {
            $stmt = $db->prepare('INSERT INTO ServiceSubcategory (service_id, subcategory_id) VALUES (?, ?)');
            $stmt->execute([$serviceId, intval($subcatId)]);
        }
    }
}

header('Location: ../../pages/services/service.php?id=' . $serviceId);
exit;
