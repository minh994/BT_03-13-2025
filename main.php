<?php

class Category {
    private $conn;
    private $table = 'categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create category
    public function create($name, $description) {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, isDeleted, created_at, updated_at) 
                  VALUES (:name, :description, 0, NOW(), NOW())";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all categories
    public function read() {
        $query = "SELECT * FROM " . $this->table . " WHERE isDeleted = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single category
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id = :id AND isDeleted = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update category
    public function update($id, $name, $description) {
        $query = "UPDATE " . $this->table . "
                  SET name = :name, 
                      description = :description,
                      updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $id = htmlspecialchars(strip_tags($id));

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Soft delete category
    public function delete($id) {
        $query = "UPDATE " . $this->table . "
                  SET isDeleted = 1,
                      updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $id = htmlspecialchars(strip_tags($id));

        // Bind parameter
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

// Kết nối database
try {
    $host = "localhost";
    $dbname = "BTL_PHP";
    $username = "root";
    $password = "123456";

    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Khởi tạo đối tượng Category
    $category = new Category($db);

    // Ví dụ sử dụng các hàm CRUD

    // 1. Tạo category mới
    $category->create("Danh mục mới", "Mô tả cho danh mục mới");

    // 2. Đọc tất cả categories
    $result = $category->read();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . "<br>";
        echo "Tên: " . $row['name'] . "<br>";
        echo "Mô tả: " . $row['description'] . "<br>";
        echo "Ngày tạo: " . $row['created_at'] . "<br>";
        echo "<hr>";
    }

    // 3. Đọc một category
    $category_info = $category->readOne(1);
    if($category_info) {
        echo "Thông tin category có ID = 1: <br>";
        echo "Tên: " . $category_info['name'] . "<br>";
        echo "Mô tả: " . $category_info['description'] . "<br>";
    }

    // 4. Cập nhật category
    $category->update(1, "Tên đã cập nhật", "Mô tả đã cập nhật");

    // 5. Xóa category (soft delete)
    $category->delete(1);

} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}

// SQL để tạo bảng categories
/*
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    isDeleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
*/
