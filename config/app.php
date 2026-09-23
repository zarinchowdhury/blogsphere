<?php
/*
|-----------------------------------
| DYNAMIC BASE URL (auto-detects the actual
| project folder name, whatever it is called
| and however it's cased on disk, instead of
| assuming a hardcoded "/Blogsphere/" folder)
|-----------------------------------
*/
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
$projectRoot  = realpath(__DIR__ . '/..') ?: '';

if ($documentRoot !== '' && $projectRoot !== '' && strpos($projectRoot, $documentRoot) === 0) {
    // Project root lives inside the web server's document root
    // (e.g. XAMPP htdocs/BlogSphere, htdocs/blogsphere, etc.)
    $relative = substr($projectRoot, strlen($documentRoot));
    $relative = str_replace('\\', '/', $relative);
    $relative = trim($relative, '/');
    $basePath = ($relative === '') ? '/' : '/' . $relative . '/';
} else {
    // Document root IS the project root (e.g. `php -S localhost:8000`
    // run from inside the project folder) or detection failed safely
    $basePath = '/';
}

define("BASE_URL", $basePath);
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
