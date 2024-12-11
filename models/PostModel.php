<?php
require_once(__DIR__ . '/../config/db.php');

class PostModel {
    
    public function getPostById($id) {
        global $conn;
        $sql = "SELECT posts.*, users.username AS author_name FROM posts
            JOIN users ON posts.author_id = users.id
            WHERE posts.id = ?";
       
        $stmt = $conn->prepare($sql);
         $stmt->bind_param("i", $id);
         $stmt->execute();
         return $stmt->get_result()->fetch_assoc();
    }

    // Fetch paginated posts without search
    public function getAllPosts($page = 1, $postsPerPage = 10) {
        global $conn;

        $offset = ($page - 1) * $postsPerPage;

        $sql = "SELECT id, title, content, image, publish_date 
                FROM posts 
                ORDER BY publish_date DESC 
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $postsPerPage, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Handle search with pagination
    public function searchPosts($searchQuery, $page = 1, $postsPerPage = 10) {
        global $conn;

        $offset = ($page - 1) * $postsPerPage;

        $sql = "SELECT id, title, content, image, publish_date 
                FROM posts 
                WHERE title LIKE ? OR content LIKE ? 
                ORDER BY publish_date DESC 
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param("ssii", $searchTerm, $searchTerm, $postsPerPage, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Count total posts for general pagination
    public function getTotalPosts() {
        global $conn;

        $sql = "SELECT COUNT(*) AS total FROM posts";
        $result = $conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    // Count total posts for search query pagination
    public function getTotalSearchResults($searchQuery) {
        global $conn;

        $sql = "SELECT COUNT(*) AS total FROM posts 
                WHERE title LIKE ? OR content LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}
?>
