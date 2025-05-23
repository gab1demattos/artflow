<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/classes/category.class.php');

$session = Session::getInstance();
if (!$session->isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: Admins only.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    if ($categoryId <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid category ID.']);
        exit();
    }

    $db = Database::getInstance();
    try {
        // Delete all subcategories for this category
        $stmt = $db->prepare('DELETE FROM Subcategory WHERE category_id = ?');
        $stmt->execute([$categoryId]);

        // Delete all services in this category
        $stmt = $db->prepare('DELETE FROM Service WHERE category_id = ?');
        $stmt->execute([$categoryId]);

        // Delete the category itself
        $stmt = $db->prepare('DELETE FROM Category WHERE id = ?');
        $stmt->execute([$categoryId]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete category.']);
    }
    exit();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}
