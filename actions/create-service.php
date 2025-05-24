<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category_id = intval($_POST['category_id'] ?? 0);
$delivery_time = intval($_POST['delivery_time'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$subcategories = $_POST['subcategories'] ?? [];

// Validate required fields
if ($title === '' || $description === '' || !$category_id || !$delivery_time || !$price) {
    $_SESSION['error'] = 'All fields except subcategories are required.';
    header('Location: /pages/new-service.php');
    exit();
}

// Include security utilities
require_once(__DIR__ . '/../database/security.php');

// Sanitize inputs
$title = Security::sanitizeInput($title);
$description = Security::sanitizeInput($description);
$category_id = Security::validateInteger($category_id) ? $category_id : 0;
$delivery_time = Security::validateInteger($delivery_time) ? $delivery_time : 0;
$price = Security::validateFloat($price) ? $price : 0;
$subcategories = is_array($subcategories) ? array_map([Security::class, 'sanitizeInput'], $subcategories) : [];

// Handle media upload (max 5 files)
$mediaPaths = [];
$primaryImage = null;
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedVideoTypes = ['video/mp4', 'video/quicktime', 'video/webm'];
$allowedTypes = array_merge($allowedImageTypes, $allowedVideoTypes);
$maxFileSize = 10 * 1024 * 1024; // 10MB limit

if (!empty($_FILES['media']['name'][0])) {
    $totalFiles = count($_FILES['media']['name']);
    if ($totalFiles > 5) {
        $_SESSION['error'] = 'You can upload a maximum of 5 files.';
        header('Location: /pages/new-service.php');
        exit();
    }
    $uploadsDir = __DIR__ . '/../images/services/';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);

    for ($i = 0; $i < $totalFiles; $i++) {
        // Create a file array structure for a single file
        $file = [
            'name' => $_FILES['media']['name'][$i],
            'type' => $_FILES['media']['type'][$i],
            'tmp_name' => $_FILES['media']['tmp_name'][$i],
            'error' => $_FILES['media']['error'][$i],
            'size' => $_FILES['media']['size'][$i],
        ];

        // Use enhanced security validation for media files
        $isImage = strpos($file['type'], 'image/') === 0;

        // For images, use our enhanced validation
        if ($isImage) {
            $validation = Security::validateImageUpload(
                $file,
                $allowedImageTypes,
                $maxFileSize
            );

            if (!$validation['valid']) {
                $_SESSION['error'] = 'File ' . $file['name'] . ': ' . $validation['error'];
                header('Location: /pages/new-service.php');
                exit();
            }

            // Create a more secure filename that preserves the extension
            $fileInfo = pathinfo($file['name']);
            $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
            $filename = uniqid('srv_', true) . $extension;
        }
        // For videos, perform basic checks
        else if (in_array($file['type'], $allowedVideoTypes)) {
            if ($file['size'] > $maxFileSize) {
                $_SESSION['error'] = 'Video file too large. Maximum size is 10MB.';
                header('Location: /pages/new-service.php');
                exit();
            }
            $filename = uniqid('srv_', true) . '_' . basename($file['name']);
        }
        // Skip files that aren't allowed
        else {
            continue;
        }

        $targetPath = $uploadsDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $mediaPaths[] = '/images/services/' . $filename;
            if ($primaryImage === null && $isImage) {
                $primaryImage = '/images/services/' . $filename;
            }
        }
    }
}
if (empty($mediaPaths)) {
    $_SESSION['error'] = 'At least one image or video is required.';
    header('Location: /pages/new-service.php');
    exit();
}
// Ensure primary image is first in the list
if ($primaryImage && ($idx = array_search($primaryImage, $mediaPaths)) !== false) {
    array_unshift($mediaPaths, array_splice($mediaPaths, $idx, 1)[0]);
}

// Insert service
$db = Database::getInstance();
$stmt = $db->prepare('INSERT INTO Service (user_id, title, description, category_id, price, delivery_time, images, videos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$images = implode(',', array_filter($mediaPaths, fn($p) => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $p)));
$videos = implode(',', array_filter($mediaPaths, fn($p) => preg_match('/\.(mp4|mov|webm)$/i', $p)));
$stmt->execute([$user['id'], $title, $description, $category_id, $price, $delivery_time, $images, $videos]);
$serviceId = $db->lastInsertId();

// Insert subcategories
if (!empty($subcategories) && is_array($subcategories)) {
    foreach ($subcategories as $subcatId) {
        $stmt = $db->prepare('INSERT INTO ServiceSubcategory (service_id, subcategory_id) VALUES (?, ?)');
        $stmt->execute([$serviceId, $subcatId]);
    }
}

header('Location: /pages/category.php?id=' . $category_id);
exit();
