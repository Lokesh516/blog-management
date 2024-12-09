<?php
require_once '../../controllers/BlogController.php';

$controller = new BlogController();
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

if ($method === 'GET') {
    $controller->fetchAllBlogs();
} elseif ($method === 'POST') {
    $controller->createBlog();
} elseif ($method === 'PUT' && preg_match('/\/(\d+)$/', $request_uri, $matches)) {
    // Parse PUT data
    parse_str(file_get_contents("php://input"), $_PUT);
    $_POST = $_PUT; // Map PUT data to $_POST for compatibility
    $controller->updateBlog($matches[1]); // Update blog using ID
} elseif ($method === 'DELETE' && preg_match('/\/(\d+)$/', $request_uri, $matches)) {
    $controller->deleteBlog($matches[1]);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>
