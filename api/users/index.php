<?php
require_once '../../controllers/UserController.php';

$controller = new UserController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getAllUsers':
            $controller->getAllUsers();
            break;
        case 'deleteUser':
            $controller->deleteUser($_POST['id']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
}
