<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database.php');

class Review
{
    public int $id;
    public int $user_id;
    public int $service_id;
    public float $rating;
    public string $comment;
    public string $created_at;
    public ?string $updated_at = null;
    public ?string $username = null;
    public ?string $service_title = null;
    public ?string $profile_image = null;

    /**
     * Constructor for Review
     */
    public function __construct(
        int $id,
        int $user_id,
        int $service_id,
        float $rating,
        string $comment,
        string $created_at = '',
        ?string $updated_at = null,
        ?string $username = null,
        ?string $service_title = null,
        ?string $profile_image = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->service_id = $service_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->username = $username;
        $this->service_title = $service_title;
        $this->profile_image = $profile_image;
    }

    /**
     * Get a review by its ID
     * 
     * @param int $id Review ID
     * @return Review|null Review object or null if not found
     */
    public static function getById(int $id): ?Review
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT Review.*, User.username
            FROM Review 
            JOIN User ON Review.user_id = User.id
            WHERE Review.id = ?
        ');
        $stmt->execute([$id]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($review) {
            return new Review(
                (int)$review['id'],
                (int)$review['user_id'],
                (int)$review['service_id'],
                (float)$review['rating'],
                $review['comment'],
                $review['created_at'],
                $review['updated_at'],
                $review['username']
            );
        }

        return null;
    }

    /**
     * Get reviews for a service
     * 
     * @param int $serviceId Service ID
     * @return array Array of Review objects
     */
    public static function getReviewsByServiceId(int $serviceId): array
    {
        $db = Database::getInstance();

        $query = '
            SELECT Review.*, User.username, User.profile_image
            FROM Review 
            JOIN User ON Review.user_id = User.id
            WHERE Review.service_id = ?
            ORDER BY Review.created_at DESC
        ';

        $stmt = $db->prepare($query);
        $stmt->execute([$serviceId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($reviews as $review) {
            $result[] = new Review(
                (int)$review['id'],
                (int)$review['user_id'],
                (int)$review['service_id'],
                (float)$review['rating'],
                $review['comment'],
                $review['created_at'],
                $review['updated_at'],
                $review['username'],
                null,
                $review['profile_image']
            );
        }

        return $result;
    }

    /**
     * Get reviews by a specific user
     * 
     * @param int $userId User ID
     * @return array Array of Review objects
     */
    public static function getReviewsByUserId(int $userId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT Review.*, User.username, Service.title as service_title
            FROM Review 
            JOIN User ON Review.user_id = User.id
            JOIN Service ON Review.service_id = Service.id
            WHERE Review.user_id = ?
            ORDER BY Review.id DESC
        ');
        $stmt->execute([$userId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($reviews as $review) {
            $result[] = new Review(
                (int)$review['id'],
                (int)$review['user_id'],
                (int)$review['service_id'],
                (float)$review['rating'],
                $review['comment'],
                $review['created_at'],
                $review['updated_at'],
                $review['username'],
                $review['service_title']
            );
        }

        return $result;
    }

    /**
     * Check if a user has already reviewed a service
     * 
     * @param int $userId User ID
     * @param int $serviceId Service ID
     * @return bool True if user has reviewed the service
     */
    public static function hasUserReviewedService(int $userId, int $serviceId): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM Review WHERE user_id = ? AND service_id = ?');
        $stmt->execute([$userId, $serviceId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Get existing review by user and service
     * 
     * @param int $userId User ID
     * @param int $serviceId Service ID
     * @return Review|null Review object or null if not found
     */
    public static function getUserReviewForService(int $userId, int $serviceId): ?Review
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT * FROM Review 
            WHERE user_id = ? AND service_id = ?
        ');
        $stmt->execute([$userId, $serviceId]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($review) {
            return new Review(
                (int)$review['id'],
                (int)$review['user_id'],
                (int)$review['service_id'],
                (float)$review['rating'],
                $review['comment'],
                $review['created_at'],
                $review['updated_at']
            );
        }

        return null;
    }

    /**
     * Create a new review
     * 
     * @param int $userId User ID
     * @param int $serviceId Service ID
     * @param float $rating Rating value (0.5 to 5)
     * @param string $comment Review text content
     * @return Review|null The created review or null if creation failed
     */
    public static function createReview(
        int $userId,
        int $serviceId,
        float $rating,
        string $comment
    ): ?Review {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('
                INSERT INTO Review (user_id, service_id, rating, comment, created_at)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
            ');

            $stmt->execute([$userId, $serviceId, $rating, $comment]);
            $reviewId = (int)$db->lastInsertId();

            return new Review(
                $reviewId,
                $userId,
                $serviceId,
                $rating,
                $comment,
                date('Y-m-d H:i:s')
            );
        } catch (PDOException $e) {
            error_log('Error creating review: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing review
     * 
     * @param int $reviewId Review ID
     * @param float $rating New rating value
     * @param string $comment New review text content
     * @return bool True if update was successful
     */
    public static function updateReview(
        int $reviewId,
        float $rating,
        string $comment
    ): bool {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('
                UPDATE Review 
                SET rating = ?, comment = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ');

            return $stmt->execute([$rating, $comment, $reviewId]);
        } catch (PDOException $e) {
            error_log('Error updating review: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a review by its ID
     * 
     * @param int $reviewId Review ID
     * @return bool True if deletion was successful
     */
    public static function deleteReview(int $reviewId): bool
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('DELETE FROM Review WHERE id = ?');
            return $stmt->execute([$reviewId]);
        } catch (PDOException $e) {
            error_log('Error deleting review: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get reviews for multiple services
     * 
     * @param array $serviceIds Array of service IDs
     * @return array Array containing reviews and rating information
     */
    public static function getReviewsByServiceIds(array $serviceIds): array
    {
        if (empty($serviceIds)) {
            return [
                'reviews' => [],
                'averageRating' => 0,
                'reviewCount' => 0
            ];
        }

        $db = Database::getInstance();
        $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));
        $stmt = $db->prepare("
            SELECT Review.*, User.username, User.profile_image, Service.title as service_title 
            FROM Review 
            JOIN User ON Review.user_id = User.id 
            JOIN Service ON Review.service_id = Service.id 
            WHERE Review.service_id IN ($placeholders) 
            ORDER BY Review.created_at DESC
        ");
        $stmt->execute($serviceIds);
        $reviewsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reviews = [];
        $totalRating = 0;
        $reviewCount = 0;

        foreach ($reviewsData as $reviewData) {
            $totalRating += (float)$reviewData['rating'];
            $reviewCount++;

            $reviews[] = new Review(
                (int)$reviewData['id'],
                (int)$reviewData['user_id'],
                (int)$reviewData['service_id'],
                (float)$reviewData['rating'],
                $reviewData['comment'],
                $reviewData['created_at'],
                $reviewData['updated_at'],
                $reviewData['username'],
                $reviewData['service_title'],
                $reviewData['profile_image']
            );
        }

        // Calculate average rating
        $averageRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0;

        return [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount
        ];
    }

    /**
     * Calculate average rating from an array of reviews
     * 
     * @param array $reviews Array of Review objects
     * @return float Average rating rounded to 1 decimal place
     */
    public static function calculateAverageRating(array $reviews): float
    {
        if (empty($reviews)) {
            return 0;
        }

        $totalRating = 0;
        $reviewCount = count($reviews);

        foreach ($reviews as $review) {
            $totalRating += $review->rating;
        }

        return round($totalRating / $reviewCount, 1);
    }

    /**
     * Convert to associative array for JSON output or template usage
     * 
     * @return array Associative array of review properties
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'username' => $this->username,
            'service_title' => $this->service_title
        ];
    }
}
