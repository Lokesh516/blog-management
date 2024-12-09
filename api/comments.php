<?php
require_once 'C:\Apache24\htdocs\blog-management\controllers\CommentController.php';


// Handle API requests
// if (isset($_GET['api'])) {
    $apiController = new CommentsController(); 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $apiController->addComment();  // Handle POST requests to add comments
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $apiController->getComments();  // Handle GET requests to fetch comments
    }
//}

?>
