<?php
require_once 'C:\Apache24\htdocs\blog-management\models\CommentModel.php';

class CommentsController {
    
    public function getComments() {
        // Get post_id from query parameters
        if (isset($_GET['post_id'])) {
            $postId = $_GET['post_id'];
            $model = new CommentModel();
            $comments = $model->getCommentsByPostId($postId);
            echo json_encode(['comments' => $comments]);
        } else {
            echo json_encode(['message' => 'Post ID is required']);
        }
    }

    public function addComment()
    {
        // Get raw POST data
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (isset($data['post_id'], $data['comment'])) {
            
            // Sanitize and trim inputs
            $postId = intval(trim($data['post_id'])); 
            $commentText = trim($data['comment']);
            $commentText = htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8');
           
            // Validate fields
            if (empty($commentText)) {
                http_response_code(400);
                echo json_encode(['message' => 'comment cannot be empty']);
                return;
            }
    
            // Instantiate the model
            $model = new CommentModel();
            
            // Attempt to save comment
            $result = $model->addComment($postId,  $commentText);
    
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Comment added successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to add comment']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid data']);
        }
    }
    
}
