<?php
// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Set proper headers for AJAX and CORS support
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a valid POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Clear session variables
        session_unset();
        session_destroy();

        // Respond with success
        echo json_encode([
            "status" => "success",
            "message" => "Logged out successfully."
        ]);
        exit;
    } catch (Exception $e) {
        // Handle unexpected errors
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Unexpected error occurred."
        ]);
    }
} else {
    // Handle invalid request methods
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
exit;
