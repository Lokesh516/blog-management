<?php
require_once('../config/db.php');

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['token']) || empty($input['password'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => $input['password']]);
        exit();
    }

    $token = $input['token'];

    // Password strength validation
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}$/', $input['password'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Password must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, and one number.'
        ]);
        exit();
    }

    $hashedPassword = password_hash($input['password'], PASSWORD_BCRYPT);

    // Query database for token existence and creation time
    $stmt = $conn->prepare("SELECT id, token_created_at FROM users WHERE reset_token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $tokenCreatedAt);
        $stmt->fetch();

        if (!$tokenCreatedAt) { // Handle the case if token_created_at is null
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Token has expired']);
            exit();
        }

        // Check token expiration (valid for 1 hour)
        $tokenExpirationTime = strtotime($tokenCreatedAt) + 3600; // Token expires 1 hour after creation
        if (time() > $tokenExpirationTime) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Token has expired']);
            exit();
        }

        // Update password and clear reset token fields
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_created_at = NULL WHERE id = ?");
        $updateStmt->bind_param('si', $hashedPassword, $id);

        if ($updateStmt->execute()) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Password has been reset successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to reset password']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
