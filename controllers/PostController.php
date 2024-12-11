<?php
require_once 'models/PostModel.php';
class PostController {

    public function showPosts() {
        $model = new PostModel();

        // Handle search query and pagination
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $postsPerPage = 10;

        if ($searchQuery) {
            $posts = $model->searchPosts($searchQuery, $currentPage, $postsPerPage);
            $totalPosts = $model->getTotalSearchResults($searchQuery);
        } else {
            $posts = $model->getAllPosts($currentPage, $postsPerPage);
            $totalPosts = $model->getTotalPosts();
        }

        // Calculate total pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass data to the view
        include 'views/home.php';
    }



    public function searchPosts($searchQuery) {
        global $conn;
        
        // Prepare the SQL query with LIKE to search for posts by title or content
        $sql = "SELECT id, title, content, publish_date FROM posts
                WHERE title LIKE ? OR content LIKE ? ORDER BY publish_date DESC";
        
        // Prepare and bind the parameters to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        
        // Return the result as an associative array
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

    // This function is responsible for fetching and displaying a single post
    public function showPost($id) {
        $model = new PostModel();
        $post = $model->getPostById($id); // Fetch the single post by ID
        include 'views/single-post.php'; // Pass the post to the view
    }
}
?>
