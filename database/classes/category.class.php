<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database.php');

class Category {
    public int $id;
    public string $category_type;
    public ?string $image;

    /**
     * Constructor for Category
     */
    public function __construct(int $id, string $category_type, ?string $image) {
        $this->id = $id;
        $this->category_type = $category_type;
        $this->image = $image;
    }

    /**
     * Get all categories
     * 
     * @return array Array of Category objects
     */
    public static function getAllCategories(): array {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM Category');
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($categories as $category) {
            $result[] = new Category(
                (int)$category['id'],
                $category['category_type'],
                $category['image']
            );
        }

        return $result;
    }

    /**
     * Get category by ID
     * 
     * @param int $id Category ID
     * @return Category|null Category object or null if not found
     */
    public static function getCategoryById(int $id): ?Category {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM Category WHERE id = ?');
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            return new Category(
                (int)$category['id'],
                $category['category_type'],
                $category['image']
            );
        }
        
        return null;
    }

    /**
     * Create a new category
     * 
     * @param string $category_type Category name/type
     * @param string|null $image Path to category image
     * @return Category|null The newly created category or null if failed
     */
    public static function createCategory(string $category_type, ?string $image = null): ?Category {
        $db = Database::getInstance();
        
        try {
            $stmt = $db->prepare('INSERT INTO Category (category_type, image) VALUES (?, ?)');
            $success = $stmt->execute([$category_type, $image]);
            
            if ($success) {
                $id = $db->lastInsertId();
                return new Category((int)$id, $category_type, $image);
            }
            
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get subcategories for this category
     * 
     * @return array Array of subcategory information
     */
    public function getSubcategories(): array {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all categories as associative arrays
     * For backward compatibility with existing code
     * 
     * @return array Array of category associative arrays
     */
    public static function getCategories(): array {
        $categoryObjects = self::getAllCategories();
        
        // Convert to associative arrays for backward compatibility
        $categories = [];
        foreach ($categoryObjects as $category) {
            $categories[] = [
                'id' => $category->getId(),
                'category_type' => $category->getCategoryType(),
                'image' => $category->getImage()
            ];
        }
        
        return $categories;
    }
    
    /**
     * Get a category by ID as an associative array
     * For backward compatibility with existing code
     * 
     * @param int $categoryId Category ID
     * @return array|null Category as associative array or null if not found
     */
    public static function getCategoryAsArrayById(int $categoryId): ?array {
        $category = self::getCategoryById($categoryId);
        
        if ($category) {
            return [
                'id' => $category->getId(),
                'category_type' => $category->getCategoryType(),
                'image' => $category->getImage()
            ];
        }
        
        return null;
    }
    
    /**
     * Get subcategories for a category by ID
     * For backward compatibility with existing code
     * 
     * @param int $categoryId Category ID
     * @return array Array of subcategory associative arrays
     */
    public static function getSubcategoriesByCategoryId(int $categoryId): array {
        $category = self::getCategoryById($categoryId);
        
        if ($category) {
            return $category->getSubcategories();
        }
        
        return [];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get category type/name
     *
     * @return string
     */
    public function getCategoryType(): string {
        return $this->category_type;
    }

    /**
     * Get category image path
     *
     * @return string|null
     */
    public function getImage(): ?string {
        return $this->image;
    }

    /**
     * Set category image path
     *
     * @param string|null $image
     * @return bool True if successful, false otherwise
     */
    public function setImage(?string $image): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE Category SET image = ? WHERE id = ?');
        $success = $stmt->execute([$image, $this->id]);
        
        if ($success) {
            $this->image = $image;
            return true;
        }
        
        return false;
    }
}
?>