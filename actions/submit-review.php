<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/classes/service.class.php');
require_once(__DIR__ . '/../database/classes/review.class.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /index.php');
    exit();
}

// Get the current user ID
$userId = $_SESSION['id'];

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
                $_SESSION['error'] = 'Service not found';
                header('Location: /pages/service.php?id=' . $serviceId);
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
                    $_SESSION['success'] = 'Your review has been updated';
                } else {
                    $_SESSION['error'] = 'Failed to update your review';
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
                    $_SESSION['success'] = 'Your review has been submitted';
                } else {
                    $_SESSION['error'] = 'Failed to submit your review';
                }
            }

            // Update the average rating for the service
            //Service::updateAverageRating($db, $serviceId);

            // Redirect back to the service page
            header('Location: /pages/service.php?id=' . $serviceId);
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'There was an error submitting your review. Please try again.';
            error_log('Review submission error: ' . $e->getMessage());
            header('Location: /pages/service.php?id=' . $serviceId);
            exit();
        }
    } else {
        // Store errors in session
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: /pages/service.php?id=' . $serviceId);
        exit();
    }
} else {
    // Not a POST request
    header('Location: /index.php');
    exit();
}
