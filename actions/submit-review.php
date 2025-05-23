<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../database/classes/review.class.php');

// Set header for JSON response
header('Content-Type: application/json');

// Initialize session and check if user is logged in
$session = Session::getInstance();
$user = $session->getUser();

if (!$user || !isset($user['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

// Get the current user ID
$userId = $user['id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
    $reviewText = isset($_POST['review_text']) ? htmlspecialchars($_POST['review_text']) : '';
    $isPublic = isset($_POST['make_public']) ? 1 : 0;

    // Basic validation
    $errors = [];

    if ($serviceId <= 0) {
        $errors[] = 'Invalid service selected';
    }

    if ($rating <= 0 || $rating > 5) {
        $errors[] = 'Please provide a rating between 0.5 and 5';
    }

    if (empty($reviewText)) {
        $errors[] = 'Please provide review text';
    }

    // If no errors, save the review
    if (empty($errors)) {
        try {
            $db = Database::getInstance();

            // Check if the service exists
            $service = Service::getServiceById($serviceId);

            if (!$service) {
                echo json_encode(['success' => false, 'error' => 'Service not found']);
                exit();
            }

            // Check if the user has already reviewed this service
            $existingReview = Review::getUserReviewForService($userId, $serviceId);

            if ($existingReview) {
                // Update the existing review
                $updated = Review::updateReview(
                    $existingReview->id,
                    $rating,
                    $reviewText,
                    $isPublic
                );

                if ($updated) {
                    echo json_encode(['success' => true, 'message' => 'Your review has been updated']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to update your review']);
                }
            } else {
                // Create a new review
                $review = Review::createReview(
                    $userId,
                    $serviceId,
                    $rating,
                    $reviewText,
                    $isPublic
                );

                if ($review) {
                    echo json_encode(['success' => true, 'message' => 'Your review has been submitted']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to submit your review']);
                }
            }

            // Update the average rating for the service
            Service::updateAverageRating($serviceId);

            exit();
        } catch (PDOException $e) {
            error_log('Review submission error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'There was an error submitting your review. Please try again.']);
            exit();
        }
    } else {
        // Return validation errors
        echo json_encode(['success' => false, 'error' => implode('<br>', $errors)]);
        exit();
    }
} else {
    // Not a POST request
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}
