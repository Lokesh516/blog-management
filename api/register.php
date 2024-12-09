<?php
require_once('../config/db.php'); 

header('Content-Type: application/json');

try {
    // Read and decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (
        empty($input['username']) ||
        empty($input['email']) ||
        empty($input['password']) ||
        !isset($input['is_admin']) 
    ) {
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required (username, email, password, is_admin).']);
        exit();
    }

    // Extract data safely
    $username = $input['username'];
    $email = $input['email'];
    $password = $input['password'];
    $is_admin = (int) $input['is_admin']; // Convert is_admin to integer (0 or 1)

    // Validate is_admin value
    if (!in_array($is_admin, [0, 1])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid value for is_admin.']);
        exit();
    }

    // **Username Validation**
    // Ensure only 3-20 characters, no numbers, no special characters, no leading/trailing spaces
    if (!preg_match('/^[a-zA-Z ]{3,20}$/', $username)) {
        http_response_code(400);
        echo json_encode(['message' => 'Username must be 3-20 characters long, contain only letters, and no spaces at the start or end.']);
        exit();
    }

    if (preg_match('/^\s+|\s+$/', $username)) { // Reject spaces at the beginning or end
        http_response_code(400);
        echo json_encode(['message' => 'Username cannot have leading or trailing spaces.']);
        exit();
    }


    // Validate password strength
    if (!validatePassword($password)) {
        http_response_code(400);
        echo json_encode([
            'message' => 'Password must be at least 8 characters long, include an uppercase letter, a number, and a special character.'
        ]);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid email format']);
        exit();
    }


    // Check if email already exists
    $checkEmailSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailSql);
    $checkStmt->bind_param('s', $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        http_response_code(409); // Conflict status code
        echo json_encode(['message' => 'User with this email already exists.']);
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into the database
    $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $username, $email, $hashedPassword, $is_admin);

    if ($stmt->execute()) {
        http_response_code(201);
        
        if ($is_admin === 1) {
            echo json_encode(['message' => 'Admin registered successfully.']);
        } else {
            echo json_encode(['message' => 'User registered successfully.']);
        }
    } else {
        throw new Exception('Database error: ' . $stmt->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => $e->getMessage()]);
}

/**
 * Function to validate password strength
 */
function validatePassword($password) {
    $passwordRegex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])^[^\s]+$/";


    if (preg_match($passwordRegex, $password)) {
        return true; // Password is valid
    }

    return false; // Password fails validation
}

?>
