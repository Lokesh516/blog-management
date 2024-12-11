<?php
require_once('../config/db.php'); // Ensure database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require_once '../vendor/autoload.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['email'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Please provide an email.']);
            exit();
        }

        $email = $input['email'];

        // Check if email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));   
            $tokenCreatedAt = date('Y-m-d H:i:s'); // Current timestamp
            
            // Store token in the database
            $updateSql = "UPDATE users SET reset_token = ?, token_created_at = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('sss', $resetToken, $tokenCreatedAt, $email);

            if ($updateStmt->execute()) {
                // Send email
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'lokeshyadav31290@gmail.com'; // Replace with your SMTP email
                    $mail->Password   = 'jvis wyhb ohwp adzc'; // Replace with your SMTP email password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    // Recipients
                    $mail->setFrom('your_email@example.com', 'Blog Management');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body    = "Click this link to reset your password: 
                        <a href='http://localhost/blog-management/views/reset_password.php?token=$resetToken'>Reset Password</a>";

                    $mail->send();

                    http_response_code(200);
                    echo json_encode(['message' => 'Password reset email sent. Please check your inbox.']);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['message' => 'Failed to send email: ' . $mail->ErrorInfo]);
                }
            } else {
                throw new Exception('Error storing reset token.');
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Email not found.']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Invalid request method.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => $e->getMessage()]);
}
?>




