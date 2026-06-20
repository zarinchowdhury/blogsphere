<?php
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BlogSphere/auth/login.php");
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

header("Location: /BlogSphere/user/user_dashboard.php");
exit;