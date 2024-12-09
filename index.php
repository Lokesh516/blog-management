<?php
require_once 'controllers/PostController.php';

// Start the session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /blog-management/views/login.php"); // Ensure the path to login.php is correct
    exit();
}

// Route logic for controllers and actions
$controllerName = isset($_GET['controller']) ? $_GET['controller'] . 'Controller' : 'PostController';
$action = isset($_GET['action']) ? $_GET['action'] : 'showPosts';

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        if (isset($_GET['id'])) {
            $controller->$action($_GET['id']);
        } else {
            $controller->$action();
        }
    } else {
        http_response_code(404);
        echo "Action not found.";
    }
} else {
    http_response_code(404);
    echo "Controller not found.";
}
?>
