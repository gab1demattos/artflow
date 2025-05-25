<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database.php');
require_once(__DIR__ . '/category.class.php');

class Service
{
    public int $id;
    public int $user_id;
    public string $title;
    public string $description;
    public int $category_id;
    public float $price;
    public int $delivery_time;
    public ?string $images;
    public ?string $videos;
    public ?string $username = null; 
    public float $avg_rating = 0; 


    public function __construct(
        int $id,
        int $user_id,
        string $title,
        string $description,
        int $category_id,
        float $price,
        int $delivery_time,
        ?string $images = null,
        ?string $videos = null,
        ?string $username = null, 
        ?float $avg_rating = 0 
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->description = $description;
        $this->category_id = $category_id;
        $this->price = $price;
        $this->delivery_time = $delivery_time;
        $this->images = $images;
        $this->videos = $videos;
        $this->username = $username; 
        $this->avg_rating = $avg_rating ?? 0; 
    }

 
    public static function getAllServices(?float $minPrice = null, ?float $maxPrice = null, ?int $maxDeliveryTime = null, ?float $minRating = 0): array
    {
        $db = Database::getInstance();
        $query = 'SELECT Service.*, User.username 
                 FROM Service 
                 JOIN User ON Service.user_id = User.id';
        $params = [];
        $whereConditions = [];

        if ($minPrice !== null) {
            $whereConditions[] = 'Service.price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $whereConditions[] = 'Service.price <= ?';
            $params[] = $maxPrice;
        }
        if ($maxDeliveryTime !== null) {
            $whereConditions[] = 'Service.delivery_time <= ?';
            $params[] = $maxDeliveryTime;
        }
        if ($minRating > 0) {
            $whereConditions[] = 'Service.avg_rating >= ?';
            $params[] = $minRating;
        }

        if (!empty($whereConditions)) {
            $query .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($services as $service) {
            $result[] = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                (float)$service['avg_rating']
            );
        }
        return $result;
    }

    public static function searchServices(PDO $db, string $search, ?float $minPrice = null, ?float $maxPrice = null, ?int $maxDeliveryTime = null, ?float $minRating = 0): array
    {
        $query = 'SELECT Service.*, User.username
                 FROM Service 
                 JOIN User ON Service.user_id = User.id
                 WHERE Service.title LIKE ?';
        $params = ['%' . $search . '%'];

        if ($minPrice !== null) {
            $query .= ' AND Service.price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $query .= ' AND Service.price <= ?';
            $params[] = $maxPrice;
        }
        if ($maxDeliveryTime !== null) {
            $query .= ' AND Service.delivery_time <= ?';
            $params[] = $maxDeliveryTime;
        }
        if ($minRating > 0) {
            $query .= ' AND Service.avg_rating >= ?';
            $params[] = $minRating;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $services = [];
        while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                (float)$service['avg_rating']
            );
        }
        return $services;
    }

    public static function searchServicesInCategories(PDO $db, string $search, array $categoryIds, ?float $minPrice = null, ?float $maxPrice = null, ?int $maxDeliveryTime = null, ?float $minRating = 0): array
    {
        if (empty($categoryIds)) {
            return self::searchServices($db, $search, $minPrice, $maxPrice, $maxDeliveryTime, $minRating);
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $query = "SELECT Service.*, User.username 
                FROM Service 
                JOIN User ON Service.user_id = User.id
                WHERE Service.title LIKE ? AND Service.category_id IN ($placeholders)";
        $params = ['%' . $search . '%'];
        $params = array_merge($params, $categoryIds);

        if ($minPrice !== null) {
            $query .= ' AND Service.price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $query .= ' AND Service.price <= ?';
            $params[] = $maxPrice;
        }
        if ($maxDeliveryTime !== null) {
            $query .= ' AND Service.delivery_time <= ?';
            $params[] = $maxDeliveryTime;
        }
        if ($minRating > 0) {
            $query .= ' AND Service.avg_rating >= ?';
            $params[] = $minRating;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $services = [];
        while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                (float)$service['avg_rating']
            );
        }
        return $services;
    }


    public static function getServicesByCategories(PDO $db, array $categoryIds, ?float $minPrice = null, ?float $maxPrice = null, ?int $maxDeliveryTime = null, ?float $minRating = 0): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $query = "SELECT Service.*, User.username 
                FROM Service 
                JOIN User ON Service.user_id = User.id
                WHERE Service.category_id IN ($placeholders)";
        $params = $categoryIds;

        if ($minPrice !== null) {
            $query .= ' AND Service.price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $query .= ' AND Service.price <= ?';
            $params[] = $maxPrice;
        }
        if ($maxDeliveryTime !== null) {
            $query .= ' AND Service.delivery_time <= ?';
            $params[] = $maxDeliveryTime;
        }
        if ($minRating > 0) {
            $query .= ' AND Service.avg_rating >= ?';
            $params[] = $minRating;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($services as $service) {
            $result[] = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                (float)$service['avg_rating']
            );
        }
        return $result;
    }


    public static function getServiceById(int $id): ?Service
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM Service WHERE id = ?');
        $stmt->execute([$id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($service) {
            return new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos']
            );
        }

        return null;
    }


    public static function getServicesByCategory(int $categoryId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id = ?');
        $stmt->execute([$categoryId]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($services as $service) {
            $serviceObj = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'] 
            );

            $result[] = $serviceObj;
        }

        return $result;
    }


    public static function countServicesByCategory(int $categoryId): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM Service WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return (int)$stmt->fetchColumn();
    }

    public static function getServicesByCategoryPaginated(int $categoryId, int $limit, int $offset): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id = ? LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($services as $service) {
            $serviceObj = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                isset($service['avg_rating']) ? (float)$service['avg_rating'] : 0
            );
            $result[] = $serviceObj;
        }
        return $result;
    }


    public static function getServicesByUserId(int $userId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.user_id = ?');
        $stmt->execute([$userId]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($services as $service) {
            $serviceObj = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'] 
            );

            $result[] = $serviceObj;
        }

        return $result;
    }

    public static function countServicesByUserId(int $userId): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM Service WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public static function getServicesByUserIdPaginated(int $userId, int $limit, int $offset): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.user_id = ? LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($services as $service) {
            $serviceObj = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                isset($service['avg_rating']) ? (float)$service['avg_rating'] : 0
            );
            $result[] = $serviceObj;
        }
        return $result;
    }

    public static function createService(
        int $user_id,
        string $title,
        string $description,
        int $category_id,
        float $price,
        int $delivery_time,
        ?string $images = null,
        ?string $videos = null,
        array $subcategories = []
    ): ?Service {
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare('INSERT INTO Service (user_id, title, description, category_id, price, delivery_time, images, videos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $success = $stmt->execute([
                $user_id,
                $title,
                $description,
                $category_id,
                $price,
                $delivery_time,
                $images,
                $videos
            ]);

            if (!$success) {
                $db->rollBack();
                return null;
            }

            $serviceId = (int)$db->lastInsertId();

            // Add subcategories
            foreach ($subcategories as $subcatId) {
                $stmt = $db->prepare('INSERT INTO ServiceSubcategory (service_id, subcategory_id) VALUES (?, ?)');
                $success = $stmt->execute([$serviceId, $subcatId]);

                if (!$success) {
                    $db->rollBack();
                    return null;
                }
            }

            $db->commit();

            return new Service(
                $serviceId,
                $user_id,
                $title,
                $description,
                $category_id,
                $price,
                $delivery_time,
                $images,
                $videos
            );
        } catch (PDOException $e) {
            $db->rollBack();
            return null;
        }
    }

    public static function updateAverageRating(int $serviceId): bool
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('
                SELECT AVG(rating) as avg_rating
                FROM Review
                WHERE service_id = ?
            ');
            $stmt->execute([$serviceId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $avgRating = $result['avg_rating'] ? (float)$result['avg_rating'] : 0;

            $updateStmt = $db->prepare('
                UPDATE Service
                SET avg_rating = ?
                WHERE id = ?
            ');

            return $updateStmt->execute([$avgRating, $serviceId]);
        } catch (PDOException $e) {
            error_log('Error updating average rating: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteServiceById(int $id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM Service WHERE id = ?');
        return $stmt->execute([$id]);
    }

   
    public function getCategory(): ?Category
    {
        return Category::getCategoryById($this->category_id);
    }

    
    public function getSubcategoryIds(): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT subcategory_id FROM ServiceSubcategory WHERE service_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    
    public function getFirstImage(): ?string
    {
        if (empty($this->images)) {
            return null;
        }

        $imageArray = array_filter(array_map('trim', explode(',', $this->images)));
        return count($imageArray) > 0 ? $imageArray[0] : null;
    }

   
    public function getImagesArray(): array
    {
        if (empty($this->images)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->images)));
    }

    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'delivery_time' => $this->delivery_time,
            'images' => $this->images,
            'videos' => $this->videos,
            'username' => $this->username ?? null,
            'avg_rating' => $this->avg_rating ?? null
        ];
    }

   
    public function getUsername(): ?string
    {
        if ($this->username === null) {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT username FROM User WHERE id = ?');
            $stmt->execute([$this->user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $this->username = $result['username'];
            }
        }

        return $this->username;
    }

    
    public static function getFilteredServicesByCategory(
        int $categoryId,
        int $limit,
        int $offset,
        ?float $priceMin,
        ?float $priceMax,
        ?float $ratingMin,
        ?float $ratingMax,
        ?int $deliveryMin,
        ?int $deliveryMax
    ): array {
        $db = Database::getInstance();
        $query = 'SELECT Service.*, User.username 
                  FROM Service 
                  JOIN User ON Service.user_id = User.id
                  WHERE Service.category_id = ?';
        $params = [$categoryId];

        if ($priceMin !== null) {
            $query .= ' AND Service.price >= ?';
            $params[] = $priceMin;
        }
        if ($priceMax !== null) {
            $query .= ' AND Service.price <= ?';
            $params[] = $priceMax;
        }

        if ($deliveryMin !== null) {
            $query .= ' AND Service.delivery_time >= ?';
            $params[] = $deliveryMin;
        }
        if ($deliveryMax !== null) {
            $query .= ' AND Service.delivery_time <= ?';
            $params[] = $deliveryMax;
        }

        if ($ratingMin !== null) {
            $query .= ' AND Service.avg_rating >= ?';
            $params[] = $ratingMin;
        }
        if ($ratingMax !== null) {
            $query .= ' AND Service.avg_rating <= ?';
            $params[] = $ratingMax;
        }

        $query .= ' LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($services as $service) {
            $result[] = new Service(
                (int)$service['id'],
                (int)$service['user_id'],
                $service['title'],
                $service['description'],
                (int)$service['category_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                $service['images'],
                $service['videos'],
                $service['username'],
                (float)$service['avg_rating']
            );
        }

        return $result;
    }

    public static function getPriceRangeByCategory(int $categoryId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT MIN(price) as min, MAX(price) as max FROM Service WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['min' => 0, 'max' => 0];
    }

    public static function getDeliveryRangeByCategory(int $categoryId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT MIN(delivery_time) as min, MAX(delivery_time) as max FROM Service WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['min' => 0, 'max' => 0];
    }
}
