<?php
require_once 'C:\Apache24\htdocs\blog-management\config\db.php';
require_once('../../controllers/CommentsController.php');

header('Content-Type: application/json');

$action = $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET['action'] : $_POST['action'];
$controller = new CommentController( $conn);

switch ($action) {
    case 'viewAll':
        $controller->handleRequest('viewAll');
        break;

    case 'delete':
        $controller->handleRequest('delete', ['id' => $_POST['id']]);
        break;

    default:
        echo json_encode(['error' => $_POST['action']]);
        break;
}
?>
