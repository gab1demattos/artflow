<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');

$session = Session::getInstance();
if (!$session->isAdmin()) {
    http_response_code(403);
    $_SESSION['error'] = 'Forbidden: Admins only.';
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['category_name'] ?? '');
    $subcategories = trim($_POST['subcategories'] ?? ''); // comma-separated
    $imagePath = null;

    // Handle image upload
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = __DIR__ . '/../images/categories/';
        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
        $filename = uniqid('cat_', true) . '_' . basename($_FILES['category_image']['name']);
        $targetPath = $uploadsDir . $filename;
        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $targetPath)) {
            $imagePath = '/images/categories/' . $filename;
        }
    }

    if ($name !== '') {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO Category (category_type, image) VALUES (?, ?)');
        $stmt->execute([$name, $imagePath]);
        $categoryId = $db->lastInsertId();

        // Insert subcategories
        if ($subcategories !== '') {
            $subs = array_map('trim', explode(',', $subcategories));
            foreach ($subs as $sub) {
                if ($sub !== '') {
                    $stmt = $db->prepare('INSERT INTO Subcategory (category_id, name) VALUES (?, ?)');
                    $stmt->execute([$categoryId, $sub]);
                }
            }
        }
        header('Location: /');
        exit();
    } else {
        $_SESSION['error'] = 'Category name is required.';
        header('Location: /');
        exit();
    }
} else {
    http_response_code(405);
    $_SESSION['error'] = 'Method Not Allowed';
    header('Location: /');
    exit();
}
