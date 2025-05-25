<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database.php');

class Category {
    public int $id;
    public string $category_type;
    public ?string $image;

    public function __construct(int $id, string $category_type, ?string $image) {
        $this->id = $id;
        $this->category_type = $category_type;
        $this->image = $image;
    }

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

    public function getSubcategories(): array {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function getCategories(): array {
        $categoryObjects = self::getAllCategories();
        
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

    public static function getSubcategoriesByCategoryId(int $categoryId): array {
        $category = self::getCategoryById($categoryId);
        
        if ($category) {
            return $category->getSubcategories();
        }
        
        return [];
    }


    public function getId(): int {
        return $this->id;
    }

 
    public function getCategoryType(): string {
        return $this->category_type;
    }


    public function getImage(): ?string {
        return $this->image;
    }


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