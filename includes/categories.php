<?php
    declare(strict_types = 1);

    require_once __DIR__ . '/database.php';

    function getCategories(): array {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM Category'); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>