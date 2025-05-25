<?php

require_once(__DIR__ . '/../../database/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['category_name'] ?? '');
    $subcategories = trim($_POST['subcategories'] ?? ''); 
    $imagePath = null;

    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = __DIR__ . '/../../images/categories/';
        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
        $filename = uniqid('cat_', true) . '_' . basename($_FILES['category_image']['name']);
        $targetPath = $uploadsDir . $filename;
        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $targetPath)) {
            $imagePath = '/images/categories/' . $filename;
        }
    }

    if ($name !== '') {
        require_once(__DIR__ . '/../../database/database.php');
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO Category (category_type, image) VALUES (?, ?)');
        $stmt->execute([$name, $imagePath]);
        $categoryId = $db->lastInsertId();

        if ($subcategories !== '') {
            $subs = array_map('trim', explode(',', $subcategories));
            foreach ($subs as $sub) {
                if ($sub !== '') {
                    $stmt = $db->prepare('INSERT INTO Subcategory (category_id, name) VALUES (?, ?)');
                    $stmt->execute([$categoryId, $sub]);
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Category name is required.']);
        exit();
    }
}
