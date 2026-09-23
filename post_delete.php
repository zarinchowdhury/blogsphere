<?php
<<<<<<< HEAD
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid post ID");
}

/* GET POST */
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found");
}

/* PERMISSION CHECK */
if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] != $post['author_id']) {
    die("Unauthorized access");
}

/* DELETE */
$stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
$stmt->execute([$id]);

header("Location: " . BASE_URL . "user/user_dashboard.php");
=======

require_once '../config/app.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ADMIN CHECK */
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Validate ID
|--------------------------------------------------------------------------
*/

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: posts.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Check if post exists (optional but safer)
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT post_id
    FROM posts
    WHERE post_id = ?
");

$stmt->execute([$id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: posts.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Post
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM posts
    WHERE post_id = ?
");

$stmt->execute([$id]);

/*
|--------------------------------------------------------------------------
| Redirect back
|--------------------------------------------------------------------------
*/

header("Location: posts.php");
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
exit;