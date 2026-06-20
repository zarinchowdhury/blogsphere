<?php
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid request");
}

/* CHECK OWNER OR ADMIN */
$stmt = $conn->prepare("
    SELECT user_id
    FROM comments
    WHERE comment_id = ?
");

$stmt->execute([$id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    die("Comment not found");
}

if ($_SESSION['user_id'] != $comment['user_id'] && ($_SESSION['role'] ?? '') !== 'admin') {
    die("Unauthorized");
}

/* DELETE */
$stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
$stmt->execute([$id]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;