<?php
require_once '../../controllers/BlogController.php';

$controller = new BlogController();
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

if ($method === 'GET') {
    $controller->fetchAllBlogs();
} elseif ($method === 'POST') {
    $method = $_SERVER['REQUEST_METHOD'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $id=null;
// Extract the ID from the URL path
   if (preg_match('/\/update\/(\d+)$/', $request_uri, $matches)) {
     $id = $matches[1];
   } 
   if($id) {
    $controller->updateBlog($id);
   } else {
    $controller->createBlog();
   }
} elseif ($method === 'DELETE' && preg_match('/\/(\d+)$/', $request_uri, $matches)) {
    $controller->deleteBlog($matches[1]);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
?>
