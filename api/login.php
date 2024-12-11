<?php
header("Content-Type: application/json");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate email and password
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Email and password are required"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Prepare SQL to check if the user exists
$sql = "SELECT id, email, password, is_admin FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start session for the user
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin']; // Store admin status

        // Return a response with the is_admin value
        http_response_code(200);
        echo json_encode([
            "message" => $user['is_admin'] == 1 ? "Admin login successful" : "User login successful",
            "is_admin" => $user['is_admin'] // Include is_admin in the response
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid password"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Email not found"]);
}

$stmt->close();
$conn->close();
?>
