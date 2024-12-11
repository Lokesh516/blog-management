<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if the user is logged in (common for all roles)
function checkLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

// Function to check if the user has admin access
function checkAdminAccess() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
        header('HTTP/1.0 403 Forbidden');
        exit('Access Denied: Admin privileges required.');
    }
}

// Function to check if the user has regular user access
function checkUserAccess() {
    if (!isset($_SESSION['user_id']) || !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
        header('HTTP/1.0 403 Forbidden');
        exit('Access Denied: Regular user privileges required.');
    }
}
