<?php

<<<<<<< HEAD
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user data (optional helper)
 */
function currentUser() {
    return [
        'user_id'  => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'role'     => $_SESSION['role'] ?? null,
    ];
}

/**
 * Require user to be logged in
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header("Location: " . (defined('BASE_URL') ? BASE_URL : '/Blogsphere/') . "auth/login.php");
        exit;
    }
}

/**
 * Check role dynamically (for future scalability)
 */
function requireRole(string $role): void {

    requireLogin();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        http_response_code(403);
        die("Access denied. Requires role: {$role}");
    }
=======
function requireLogin() {
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
}