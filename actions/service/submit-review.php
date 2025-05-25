<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/classes/service.class.php');
require_once(__DIR__ . '/../../database/classes/review.class.php');
require_once(__DIR__ . '/../../database/security/security.php');

header('Content-Type: application/json');

$session = Session::getInstance();
$user = $session->getUser();

if (!$user || !isset($user['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
    $comment = isset($_POST['review_text']) ? Security::sanitizeInput($_POST['review_text']) : '';
    $exchangeId = isset($_POST['exchange_id']) ? intval($_POST['exchange_id']) : 0;

    $errors = [];

    if ($serviceId <= 0) {
        $errors[] = 'Invalid service selected';
    }
    if ($exchangeId <= 0) {
        $errors[] = 'Invalid order (exchange) selected';
    }
    if ($rating <= 0 || $rating > 5) {
        $errors[] = 'Please provide a rating between 0.5 and 5';
    }
    if (empty($comment)) {
        $errors[] = 'Please provide review text';
    }

    if (empty($errors)) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM Exchange WHERE id = ? AND client_id = ? AND service_id = ? AND status = "completed"');
        $stmt->execute([$exchangeId, $userId, $serviceId]);
        $exchange = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$exchange) {
            echo json_encode(['success' => false, 'error' => 'You can only review completed orders you purchased.']);
            exit();
        }
        $stmt = $db->prepare('SELECT 1 FROM Review WHERE user_id = ? AND exchange_id = ?');
        $stmt->execute([$userId, $exchangeId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'You have already reviewed this order.']);
            exit();
        }
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance();

            $service = Service::getServiceById($serviceId);

            if (!$service) {
                echo json_encode(['success' => false, 'error' => 'Service not found']);
                exit();
            }

            $review = Review::createReview(
                $userId,
                $serviceId,
                $rating,
                $comment,
                $exchangeId
            );

            if ($review) {
                echo json_encode(['success' => true, 'message' => 'Your review has been submitted']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to submit your review']);
            }

            Service::updateAverageRating($serviceId);

            exit();
        } catch (PDOException $e) {
            error_log('Review submission error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'There was an error submitting your review. Please try again.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => implode('<br>', $errors)]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}
