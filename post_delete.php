<?php

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
exit;