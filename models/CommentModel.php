<?php
require_once(__DIR__ . '/../config/db.php');

class CommentModel {
    
    // Get comments by post ID
    public function getCommentsByPostId($postId) {
        global $conn;
        
        $sql = "SELECT * FROM comments WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $comments = [];
        
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        
        return $comments;
    }

    // Add a comment to a post
    public function addComment($postId, $commentText) {
        global $conn;
        session_start();
        
        // Fetch user name from database using session user_id
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            
            // Query to get the username from the database
            $stmtUser = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $stmtUser->bind_param("i", $userId);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();
    
            if ($resultUser->num_rows > 0) {
                $user = $resultUser->fetch_assoc();
                $userName = $user['username'];
    
                // Insert the comment into the database
                $sql = "INSERT INTO comments (post_id, user_name, comment) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $postId, $userName, $commentText);
                
                return $stmt->execute();
            } else {
                return false; // User not found
            }
        }
    
        return false; // Session user_id not set
    }
    
}
