<?php
session_start();

/*
|-----------------------------------
| SESSION TIMEOUT (10 MINUTES)
|-----------------------------------
*/

$timeoutDuration = 600; // 10 minutes = 600 seconds

// If user is logged in
if (isset($_SESSION['user_id'])) {

    // If last activity exists
    if (isset($_SESSION['LAST_ACTIVITY'])) {

        $elapsed = time() - $_SESSION['LAST_ACTIVITY'];

        // If more than 10 minutes inactive → logout
        if ($elapsed > $timeoutDuration) {

            session_unset();
            session_destroy();

            header("Location: /BlogSphere/auth/login.php?timeout=1");
            exit;
        }
    }

    // Update last activity time
    $_SESSION['LAST_ACTIVITY'] = time();
}

require_once __DIR__ . '/database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Database connection failed");
}

define("BASE_URL", "http://localhost/BlogSphere/");