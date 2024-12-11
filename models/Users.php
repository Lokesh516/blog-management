<?php
class User
{
    private $conn;

    public function __construct()
    {
        require_once __DIR__ . '/../config/db.php'; // Import db.php
        $this->conn = $conn; // Use the database connection from db.php
    }

    // Fetch all users
    // Fetch all registered users (excluding admins)
public function getAllUsers()
{
    $query = "SELECT id, username, email, is_admin FROM users WHERE is_admin = 0";
    $result = mysqli_query($this->conn, $query);
    $users = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}


    // Update user status (activate/deactivate)
    public function updateUserStatus($id, $status)
    {
        $query = "UPDATE users SET is_active = $status WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    // Delete user
    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
?>
