<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database.php');
require_once(__DIR__ . '/category.class.php');

class Service {
    public int $id;
    public int $user_id;
    public string $title;
    public string $description;
    public int $category_id;
    public float $price;
    public int $delivery_time;
    public ?string $images;
    public ?string $videos;
    public ?string $username = null; // Added username property
    
    /**
     * Constructor for Service
     */
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
        ?string $username = null // Added username parameter
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
        $this->username = $username; // Initialize username property
    }
    
    /**
     * Get service by ID
     * 
     * @param int $id Service ID
     * @return Service|null Service object or null if not found
     */
    public static function getServiceById(int $id): ?Service {
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
    
    /**
     * Get services by category ID
     * 
     * @param int $categoryId Category ID
     * @return array Array of Service objects
     */
    public static function getServicesByCategory(int $categoryId): array {
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
                $service['username'] // Pass the username
            );
            
            $result[] = $serviceObj;
        }
        
        return $result;
    }
    
    /**
     * Count services by category (for pagination)
     * @param int $categoryId
     * @return int
     */
    public static function countServicesByCategory(int $categoryId): int {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM Service WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get services by category with pagination
     * @param int $categoryId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getServicesByCategoryPaginated(int $categoryId, int $limit, int $offset): array {
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
                $service['username']
            );
            $result[] = $serviceObj;
        }
        return $result;
    }
    
    /**
     * Get services by user ID
     * 
     * @param int $userId User ID
     * @return array Array of Service objects
     */
    public static function getServicesByUserId(int $userId): array {
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
                $service['username'] // Pass the username
            );
            
            $result[] = $serviceObj;
        }
        
        return $result;
    }
    
    /**
     * Count services by user (for pagination)
     * @param int $userId
     * @return int
     */
    public static function countServicesByUserId(int $userId): int {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM Service WHERE user_id = ?');
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get services by user with pagination
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getServicesByUserIdPaginated(int $userId, int $limit, int $offset): array {
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
                $service['username']
            );
            $result[] = $serviceObj;
        }
        return $result;
    }
    
    /**
     * Create a new service
     * 
     * @return Service|null The newly created service or null if failed
     */
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
                $user_id, $title, $description, $category_id, $price, $delivery_time, $images, $videos
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
    
    /**
     * Delete a service by ID
     * @param int $id
     * @return bool
     */
    public static function deleteServiceById(int $id): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM Service WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    /**
     * Get the category for this service
     * 
     * @return Category|null The category for this service
     */
    public function getCategory(): ?Category {
        return Category::getCategoryById($this->category_id);
    }
    
    /**
     * Get subcategories for this service
     * 
     * @return array Array of subcategory IDs
     */
    public function getSubcategoryIds(): array {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT subcategory_id FROM ServiceSubcategory WHERE service_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Get the first image for this service
     * 
     * @return string|null The first image path or null if no images
     */
    public function getFirstImage(): ?string {
        if (empty($this->images)) {
            return null;
        }
        
        $imageArray = array_filter(array_map('trim', explode(',', $this->images)));
        return count($imageArray) > 0 ? $imageArray[0] : null;
    }
    
    /**
     * Get all image paths as array
     * 
     * @return array Array of image paths
     */
    public function getImagesArray(): array {
        if (empty($this->images)) {
            return [];
        }
        
        return array_filter(array_map('trim', explode(',', $this->images)));
    }
    
    /**
     * Convert to associative array for JSON output or template usage
     * 
     * @return array Associative array of service properties
     */
    public function toArray(): array {
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
            'username' => $this->username ?? null
        ];
    }
    
    /**
     * Get the username of the service provider
     * 
     * @return string|null Username or null if not available
     */
    public function getUsername(): ?string {
        if ($this->username === null) {
            // If username is not set, fetch it from the database
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

    /**
     * Get all services
     * 
     * @return array Array of Service objects
     */
    public static function getAllServices(?float $minPrice = null, ?float $maxPrice = null): array {
        $db = Database::getInstance();
        $query = 'SELECT * FROM Service';
        $params = [];

        if ($minPrice !== null || $maxPrice !== null) {
            $query .= ' WHERE';
            if ($minPrice !== null) {
                $query .= ' price >= ?';
                $params[] = $minPrice;
            }
            if ($maxPrice !== null) {
                $query .= ($minPrice !== null ? ' AND' : '') . ' price <= ?';
                $params[] = $maxPrice;
            }
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
                $service['videos']
            );
        }

        return $result;
    }

    public static function searchServices(PDO $db, string $search, ?float $minPrice = null, ?float $maxPrice = null): array {
        $query = 'SELECT * FROM Service WHERE title LIKE ?';
        $params = ['%' . $search . '%'];

        if ($minPrice !== null) {
            $query .= ' AND price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $query .= ' AND price <= ?';
            $params[] = $maxPrice;
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
                $service['videos']
            );
        }
        return $services;
    }

    /**
     * Get services by multiple category IDs
     * 
     * @param PDO $db Database connection
     * @param array $categoryIds Array of category IDs
     * @return array Array of Service objects
     */
    public static function getServicesByCategories(PDO $db, array $categoryIds, ?float $minPrice = null, ?float $maxPrice = null): array {
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $query = "SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id IN ($placeholders)";
        $params = $categoryIds;

        if ($minPrice !== null) {
            $query .= ' AND price >= ?';
            $params[] = $minPrice;
        }
        if ($maxPrice !== null) {
            $query .= ' AND price <= ?';
            $params[] = $maxPrice;
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
                $service['username']
            );
        }
        return $result;
    }

    /**
     * Get filtered services by category with pagination
     * @param int $categoryId
     * @param int $limit
     * @param int $offset
     * @param float|null $priceMin
     * @param float|null $priceMax
     * @param float|null $ratingMin
     * @param float|null $ratingMax
     * @param int|null $deliveryMin
     * @param int|null $deliveryMax
     * @return array
     */
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

        $query = 'SELECT Service.*, User.username FROM Service JOIN User ON Service.user_id = User.id WHERE Service.category_id = ?';
        $params = [$categoryId];

        // Apply price range filter
        if ($priceMin !== null) {
            $query .= ' AND Service.price >= ?';
            $params[] = $priceMin;
        }
        if ($priceMax !== null) {
            $query .= ' AND Service.price <= ?';
            $params[] = $priceMax;
        }

        // Apply rating range filter (assuming a rating column exists)
        if ($ratingMin !== null) {
            $query .= ' AND Service.rating >= ?';
            $params[] = $ratingMin;
        }
        if ($ratingMax !== null) {
            $query .= ' AND Service.rating <= ?';
            $params[] = $ratingMax;
        }

        // Apply delivery time range filter
        if ($deliveryMin !== null) {
            $query .= ' AND Service.delivery_time >= ?';
            $params[] = $deliveryMin;
        }
        if ($deliveryMax !== null) {
            $query .= ' AND Service.delivery_time <= ?';
            $params[] = $deliveryMax;
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
                $service['username']
            );
        }

        return $result;
    }
    
}
?>