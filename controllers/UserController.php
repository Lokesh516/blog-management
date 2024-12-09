<?php
require_once 'C:\Apache24\htdocs\blog-management\models\Users.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Get all users
    public function getAllUsers()
    {
        $users = $this->userModel->getAllUsers();
        echo json_encode(['success' => true, 'users' => $users]);
    }

    // Delete user
    public function deleteUser($id)
    {
        $userId = $id;

        if ($this->userModel->deleteUser($userId)) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
        }
    }
}
?>
